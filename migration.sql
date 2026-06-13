-- ============================================================
-- MyIntern — Migration: Evaluation & Reminder Log tables
-- Run this once in your database before using the new features
-- ============================================================

-- ── 1. Evaluation table ──────────────────────────────────────
CREATE TABLE IF NOT EXISTS evaluation (
    evaluation_id      INT AUTO_INCREMENT PRIMARY KEY,
    student_id         INT         NOT NULL,
    lecturer_id        INT         NOT NULL,
    professionalism    TINYINT     NOT NULL DEFAULT 3 CHECK (professionalism BETWEEN 1 AND 5),
    technical_skills   TINYINT     NOT NULL DEFAULT 3 CHECK (technical_skills BETWEEN 1 AND 5),
    communication      TINYINT     NOT NULL DEFAULT 3 CHECK (communication BETWEEN 1 AND 5),
    punctuality        TINYINT     NOT NULL DEFAULT 3 CHECK (punctuality BETWEEN 1 AND 5),
    initiative         TINYINT     NOT NULL DEFAULT 3 CHECK (initiative BETWEEN 1 AND 5),
    teamwork           TINYINT     NOT NULL DEFAULT 3 CHECK (teamwork BETWEEN 1 AND 5),
    overall_grade      CHAR(1)     NOT NULL DEFAULT 'B' CHECK (overall_grade IN ('A','B','C','D','F')),
    comments           TEXT,
    recommend          TINYINT(1)  NOT NULL DEFAULT 0,
    eval_date          DATE,
    created_at         TIMESTAMP   DEFAULT CURRENT_TIMESTAMP,
    updated_at         TIMESTAMP   DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_eval_student  FOREIGN KEY (student_id)  REFERENCES student(student_id)   ON DELETE CASCADE,
    CONSTRAINT fk_eval_lecturer FOREIGN KEY (lecturer_id) REFERENCES lecturer(lecturer_id) ON DELETE CASCADE,
    UNIQUE KEY uq_eval_per_student (student_id, lecturer_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 2. Reminder Log table ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS reminder_log (
    reminder_id    INT AUTO_INCREMENT PRIMARY KEY,
    student_id     INT         NOT NULL,
    lecturer_id    INT         NOT NULL,
    reminder_type  VARCHAR(50) NOT NULL DEFAULT 'zero_application',
    sent_at        TIMESTAMP   DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_remind_student  FOREIGN KEY (student_id)  REFERENCES student(student_id)   ON DELETE CASCADE,
    CONSTRAINT fk_remind_lecturer FOREIGN KEY (lecturer_id) REFERENCES lecturer(lecturer_id) ON DELETE CASCADE,
    UNIQUE KEY uq_reminder_per_student (student_id, lecturer_id, reminder_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ── 3. Add enrollment_date to student if not present ────────
-- Skip if already exists
ALTER TABLE student
    ADD COLUMN IF NOT EXISTS enrollment_date DATE DEFAULT (CURDATE());

-- ── Verification queries (optional, comment out before running) ──
-- SHOW TABLES LIKE 'evaluation';
-- SHOW TABLES LIKE 'reminder_log';
-- DESCRIBE evaluation;
-- DESCRIBE reminder_log;
