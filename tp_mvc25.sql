-- Database untuk project praktikum MVC: pengelolaan data dosen
-- Nama database disamakan dengan connection.php pada project awal.

CREATE DATABASE IF NOT EXISTS tp_mvc25
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE tp_mvc25;

-- Tabel awal yang dibutuhkan oleh index.php, create.php, edit.php, dan delete.php.
CREATE TABLE IF NOT EXISTS lecturers (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    nidn VARCHAR(20) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    join_date DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

-- Tabel sengaja dibuat kosong terlebih dahulu.
-- Data dosen dapat ditambahkan melalui fitur Create pada aplikasi.

-- Contoh INSERT manual (opsional):
-- INSERT INTO lecturers (name, nidn, phone, join_date)
-- VALUES ('Nama Dosen', '0012345678', '081234567890', '2024-01-15');


-- =====================================================
-- TABEL TAMBAHAN PERTAMA: COURSES
-- =====================================================

CREATE TABLE IF NOT EXISTS courses (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) NOT NULL,
    sks TINYINT UNSIGNED NOT NULL,
    lecturer_id INT UNSIGNED NOT NULL,

    PRIMARY KEY (id),

    UNIQUE KEY uq_courses_course_code (course_code),

    CONSTRAINT fk_courses_lecturers
        FOREIGN KEY (lecturer_id)
        REFERENCES lecturers(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

  -- =====================================================
-- TABEL TAMBAHAN KEDUA: COURSE_SCHEDULES
-- =====================================================

CREATE TABLE IF NOT EXISTS course_schedules (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    course_id INT UNSIGNED NOT NULL,

    day_of_week ENUM(
        'Senin',
        'Selasa',
        'Rabu',
        'Kamis',
        'Jumat',
        'Sabtu'
    ) NOT NULL,

    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    room VARCHAR(50) NOT NULL,

    PRIMARY KEY (id),

    CONSTRAINT fk_course_schedules_courses
        FOREIGN KEY (course_id)
        REFERENCES courses(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE

) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci;

INSERT INTO lecturers
    (name, nidn, phone, join_date)
VALUES
    ('Rosa Ariani Sukamto, M.T.', '1988015503', '085221409989', '2002-01-10'),
    ('Dr. Yudi Wibisono, ST,M.T.', '1989025699', '081290989796', '2007-02-15'),
    ('Rizky Rachman Judhie Putra, M.Kom.', '1990034554', '081345532334', '2010-03-20'),
    ('Dr.Herbert, S.Kom., MT..', '1991041222', '081267790550', '2000-01-12'),
    ('Dr.Rasim, S.T., M.T.', '1992050099', '081066955400', '2001-02-18');

INSERT INTO courses
    (course_name, course_code, sks, lecturer_id)
VALUES
    ('Algoritma dan Pemrograman', 'IF-001', 3,
    (SELECT id FROM lecturers WHERE nidn = '1988015503')),
    ('Artificial Intelligence', 'IF-002', 3,
    (SELECT id FROM lecturers WHERE nidn = '1989025699')),
    ('Sistem Digital Komputer', 'IF-003', 4,
    (SELECT id FROM lecturers WHERE nidn = '1990034554')),
    ('Pemrograman Web & Mobile', 'IF-004', 3,
    (SELECT id FROM lecturers WHERE nidn = '1991041222')),
    ('Teori Bahasa dan Automata', 'IF-005', 3,
    (SELECT id FROM lecturers WHERE nidn = '1992050099'));

INSERT INTO course_schedules
    (course_id, day_of_week, start_time, end_time, room)
VALUES
    ((SELECT id FROM courses WHERE course_code = 'TP8-IF-001'),
    'Senin', '08:00:00', '10:30:00', 'Lab Komputer 1'),
    ((SELECT id FROM courses WHERE course_code = 'TP8-IF-002'),
    'Selasa', '10:00:00', '12:30:00', 'Ruang 201'),
    ((SELECT id FROM courses WHERE course_code = 'TP8-IF-003'),
    'Rabu', '13:00:00', '15:30:00', 'Lab Komputer 2'),
    ((SELECT id FROM courses WHERE course_code = 'TP8-IF-004'),
    'Kamis', '08:00:00', '10:30:00', 'Ruang 305'),
    ((SELECT id FROM courses WHERE course_code = 'TP8-IF-005'),
    'Jumat', '13:00:00', '15:30:00', 'Ruang 301');