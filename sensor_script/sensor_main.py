#!/usr/bin/env python3
"""
sensor_main.py — Script Raspberry Pi untuk Hydroponics Farm
============================================================
Fungsi:
  1. Baca sensor (suhu, kelembaban, tekanan, UV, pH, TDS, DO)
  2. Tentukan kondisi dan nilai aktuator (0 / 50 / 100)
  3. Kirim ke Laravel API → dapat respons berisi durasi timer
  4. Jalankan background thread timer untuk setiap aktuator
  5. Setelah timer selesai → panggil PATCH /api/actuator-done/{id}

Logika Durasi:
  Nilai 0   → aktuator mati, tidak ada timer
  Nilai 50  → aktif 30 detik
  Nilai 100 → aktif 60 detik

Sesuaikan variabel SENSOR_* dan fungsi read_sensors() dengan
hardware yang digunakan (DHT22, BME280, sensor analog, dll).
"""

import time
import threading
import logging
import requests

# ─────────────────────────────────────────────
# KONFIGURASI — sesuaikan dengan kondisi lapangan
# ─────────────────────────────────────────────
API_BASE_URL   = "http://203.194.115.209:8000/api"
# Ganti dengan IP server Laravel yang dapat dijangkau dari Pi
# Contoh local: "http://192.168.1.100:8000/api"

INTERVAL_DETIK = 60          # Jeda antar pengiriman data (detik)
LOG_LEVEL      = logging.DEBUG

# ─────────────────────────────────────────────
# SETUP LOGGING
# ─────────────────────────────────────────────
logging.basicConfig(
    level=LOG_LEVEL,
    format="%(asctime)s [%(levelname)s] %(message)s",
    datefmt="%Y-%m-%d %H:%M:%S",
)
log = logging.getLogger(__name__)


# ─────────────────────────────────────────────
# FUNGSI: BACA SENSOR
# ─────────────────────────────────────────────
def read_sensors() -> dict:
    """
    Baca semua sensor dan kembalikan dict data mentah.
    !! SESUAIKAN bagian ini dengan hardware Anda !!

    Contoh untuk DHT22 + BME280:
        import board, adafruit_dht, adafruit_bme280
        dht = adafruit_dht.DHT22(board.D4)
        i2c = board.I2C()
        bme = adafruit_bme280.Adafruit_BME280_I2C(i2c)

        return {
            "temperature": dht.temperature,
            "humidity":    dht.humidity,
            "pressure":    bme.pressure,
            "index_uv":    read_uv_sensor(),   # fungsi custom Anda
            "ph":          read_ph(),           # ADC / sensor digital
            "tds":         read_tds(),
            "do":          read_do(),
        }

    Nilai di bawah ini adalah SIMULASI — hapus/ganti saat deploy nyata.
    """
    import random
    return {
        "temperature": round(random.uniform(25.0, 38.0), 2),
        "humidity":    round(random.uniform(55.0, 90.0), 2),
        "pressure":    round(random.uniform(1005.0, 1020.0), 2),
        "index_uv":    round(random.uniform(0.0, 11.0), 2),
        "ph":          round(random.uniform(5.5, 7.5), 2),
        "tds":         round(random.uniform(600.0, 1400.0), 2),
        "do":          round(random.uniform(6.0, 10.0), 2),
    }


# ─────────────────────────────────────────────
# FUNGSI: TENTUKAN KONDISI & NILAI AKTUATOR
# ─────────────────────────────────────────────
def classify(data: dict) -> tuple[str, int, int]:
    """
    Kembalikan (conditions, sprinkler_value, fan_value)
    berdasarkan data sensor.

    Nilai aktuator: 0 = mati | 50 = sedang (30 dtk) | 100 = penuh (60 dtk)

    Sesuaikan threshold ini dengan kebutuhan tanaman Anda.
    """
    temp     = data["temperature"]
    humidity = data["humidity"]

    # Contoh logika sederhana — sesuaikan dengan kebutuhan
    if temp >= 35:
        conditions = "Sangat Panas"
        sprinkler  = 100   # aktif penuh 60 detik
        fan        = 100
    elif temp >= 30:
        conditions = "Panas"
        sprinkler  = 50    # aktif sedang 30 detik
        fan        = 50
    elif humidity < 60:
        conditions = "Kering"
        sprinkler  = 50
        fan        = 0
    else:
        conditions = "Normal"
        sprinkler  = 0
        fan        = 0

    return conditions, sprinkler, fan


# ─────────────────────────────────────────────
# FUNGSI: KONTROL AKTUATOR FISIK (GPIO)
# ─────────────────────────────────────────────
def set_sprinkler(state: bool):
    """
    Nyalakan / matikan sprinkler via GPIO.
    !! Sesuaikan pin GPIO dengan wiring Anda !!
    """
    # Contoh implementasi GPIO (aktifkan jika pakai RPi.GPIO):
    # import RPi.GPIO as GPIO
    # SPRINKLER_PIN = 17
    # GPIO.setmode(GPIO.BCM)
    # GPIO.setup(SPRINKLER_PIN, GPIO.OUT)
    # GPIO.output(SPRINKLER_PIN, GPIO.HIGH if state else GPIO.LOW)
    log.info(f"  [GPIO] Sprinkler → {'ON' if state else 'OFF'}")


def set_fan(state: bool):
    """
    Nyalakan / matikan fan via GPIO.
    !! Sesuaikan pin GPIO dengan wiring Anda !!
    """
    # FAN_PIN = 27
    # GPIO.output(FAN_PIN, GPIO.HIGH if state else GPIO.LOW)
    log.info(f"  [GPIO] Fan       → {'ON' if state else 'OFF'}")


# ─────────────────────────────────────────────
# FUNGSI: TIMER THREAD AKTUATOR
# ─────────────────────────────────────────────
def actuator_timer(
    aktuator_id: int,
    sprinkler_duration: int,
    fan_duration: int,
    sprinkler_value: int,
    fan_value: int,
):
    """
    Dijalankan di background thread.
    - Nyalakan aktuator fisik via GPIO
    - Tunggu sesuai durasinya
    - Matikan aktuator fisik
    - Update database via PATCH /api/actuator-done/{id}

    Setiap aktuator bisa punya durasi berbeda, jadi kita
    pakai dua timer independen dalam satu thread.
    """
    sprinkler_done = (sprinkler_value == 0)   # Langsung selesai jika 0
    fan_done       = (fan_value == 0)

    # Nyalakan aktuator fisik
    if sprinkler_value > 0:
        set_sprinkler(True)
    if fan_value > 0:
        set_fan(True)

    # Catat waktu mulai
    sprinkler_start = time.monotonic()
    fan_start       = time.monotonic()

    log.info(
        f"  [TIMER] Aktuator #{aktuator_id} mulai — "
        f"Sprinkler {sprinkler_duration}s / Fan {fan_duration}s"
    )

    while not (sprinkler_done and fan_done):
        time.sleep(1)
        elapsed = time.monotonic()

        # Cek apakah sprinkler sudah cukup lama menyala
        if not sprinkler_done and (elapsed - sprinkler_start) >= sprinkler_duration:
            set_sprinkler(False)
            sprinkler_done = True
            log.info(f"  [TIMER] Sprinkler #{aktuator_id} mati setelah {sprinkler_duration}s")

        # Cek apakah fan sudah cukup lama menyala
        if not fan_done and (elapsed - fan_start) >= fan_duration:
            set_fan(False)
            fan_done = True
            log.info(f"  [TIMER] Fan #{aktuator_id} mati setelah {fan_duration}s")

    # Semua aktuator sudah mati — update database
    notify_actuator_done(
        aktuator_id=aktuator_id,
        sprinkler_off=(sprinkler_value > 0),
        fan_off=(fan_value > 0),
    )


def notify_actuator_done(aktuator_id: int, sprinkler_off: bool, fan_off: bool):
    """
    Kirim PATCH ke Laravel agar database diupdate dengan waktu mati aktuator.
    """
    url     = f"{API_BASE_URL}/actuator-done/{aktuator_id}"
    payload = {
        "sprinkler_off": sprinkler_off,
        "fan_off":       fan_off,
    }

    try:
        resp = requests.patch(url, json=payload, timeout=10)
        resp.raise_for_status()
        log.info(f"  [API] Actuator-done dikirim → {resp.json()}")
    except requests.RequestException as e:
        log.error(f"  [API] Gagal kirim actuator-done: {e}")


# ─────────────────────────────────────────────
# FUNGSI: KIRIM DATA KE API
# ─────────────────────────────────────────────
def send_sensor_data(data: dict, conditions: str, sprinkler: int, fan: int) -> dict | None:
    """
    POST data sensor ke Laravel.
    Kembalikan respons JSON jika berhasil, None jika gagal.
    """
    url     = f"{API_BASE_URL}/sensor-data"
    payload = {
        "temperature": data["temperature"],
        "humidity":    data["humidity"],
        "pressure":    data["pressure"],
        "index_uv":    data["index_uv"],
        "conditions":  conditions,
        "sprinkler":   sprinkler,
        "blower":      fan,
    }

    try:
        resp = requests.post(url, json=payload, timeout=15)
        resp.raise_for_status()
        result = resp.json()
        log.info(f"  [API] Data terkirim → aktuator_id={result.get('aktuator_id')}")
        return result
    except requests.RequestException as e:
        log.error(f"  [API] Gagal mengirim data sensor: {e}")
        return None


# ─────────────────────────────────────────────
# MAIN LOOP
# ─────────────────────────────────────────────
def main():
    log.info("=" * 50)
    log.info("  HydroFarm Sensor Started")
    log.info(f"  API: {API_BASE_URL}")
    log.info(f"  Interval: {INTERVAL_DETIK}s")
    log.info("=" * 50)

    while True:
        try:
            # 1. Baca sensor
            data = read_sensors()
            log.info(
                f"Sensor → suhu={data['temperature']}°C  "
                f"humidity={data['humidity']}%  "
                f"UV={data['index_uv']}"
            )

            # 2. Klasifikasi kondisi & tentukan aktuator
            conditions, sprinkler, fan = classify(data)
            log.info(
                f"Kondisi: {conditions} | "
                f"Sprinkler={sprinkler} | Fan={fan}"
            )

            # 3. Kirim ke API
            result = send_sensor_data(data, conditions, sprinkler, fan)

            # 4. Jika ada aktuator yang perlu aktif → jalankan timer di background
            if result and (sprinkler > 0 or fan > 0):
                aktuator_id        = result["aktuator_id"]
                sprinkler_duration = result.get("sprinkler_duration", 0)
                fan_duration       = result.get("fan_duration", 0)

                t = threading.Thread(
                    target=actuator_timer,
                    args=(aktuator_id, sprinkler_duration, fan_duration, sprinkler, fan),
                    daemon=True,       # Thread akan mati jika program utama berhenti
                    name=f"actuator-{aktuator_id}",
                )
                t.start()
                log.info(
                    f"  [THREAD] Timer dimulai → "
                    f"sprinkler {sprinkler_duration}s, fan {fan_duration}s"
                )
            else:
                log.info("  Semua aktuator OFF — tidak ada timer")

        except Exception as e:
            log.exception(f"Error pada loop utama: {e}")

        # 5. Tunggu interval berikutnya
        log.info(f"  Menunggu {INTERVAL_DETIK}s sebelum pembacaan berikutnya...\n")
        time.sleep(INTERVAL_DETIK)


if __name__ == "__main__":
    main()
