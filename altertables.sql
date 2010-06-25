ALTER TABLE t_exercise_plans_daily ADD COLUMN week_date date NOT NULL;
ALTER TABLE t_exercise_plans_daily ADD CONSTRAINT t_exercise_plans_daily_fk_weekly FOREIGN KEY (week_date, userid) REFERENCES t_exercise_plans_weekly (week_date, userid) ON UPDATE NO ACTION ON DELETE NO ACTION;

