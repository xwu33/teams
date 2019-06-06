/*before running this script, make sure that the old tables are moved to
  the bioproc_old db. Also make sure the bioproc_users csv file is imported as
  mybama_map and that the myBama Id field is set to primary*/

USE bioproc_bioproc;

/*insert new myBama users*/

INSERT INTO users (username,password,email,phone_number,first_name,last_name,active,verified,is_cas,deleted_at,created_at,updated_at)
  (SELECT mybama_id,'isCas',M.email,'',M.first_name,M.last_name,1,1,M.is_cas,CASE WHEN archived=1 THEN NOW() ELSE NULL END,NOW(),NOW() FROM bioproc_old.mybama_map AS M LEFT JOIN users AS U ON M.mybama_id=U.username WHERE U.username IS NULL);

/*set up roles*/
/*student=>Student; faculty=>Faculty; admin=>Admin*/

INSERT IGNORE INTO model_has_roles (role_id,model_id,model_type)
  (SELECT R.id,U.id,'bioproc\\User' FROM bioproc_old.mybama_map AS M JOIN users AS U ON M.`mybama_id`=U.username JOIN roles AS R ON UPPER(M.user_type)=UPPER(R.Name));


/*we need to create a temp linking column for old exam IDs*/
ALTER TABLE exams ADD COLUMN prev_id INT UNSIGNED NOT NULL;

/*now we insert all the exams and link them up to their new user IDs*/
INSERT INTO exams (
  prev_id,
  owner_id,
  course_name,
  location,
  max_proctors,
  max_students,
  school_year,
  date,
  start_time,
  end_time,
  locked,
  deleted_at,
  created_at,
  updated_at
)
(
  SELECT
  E.id,
  U.id,
  E.course_name,
  E.location,
  E.num_of_proctors,
  E.num_of_students,
  0,
  DATE_FORMAT(start_datetime,"%Y-%m-%d 00:00:00"),
  DATE_FORMAT(start_datetime,"%H:%i:%s"),
  DATE_FORMAT(end_datetime,"%H:%i:%s"),
  CASE WHEN locked=1 OR start_datetime < NOW() THEN 1 ELSE 0 END,
  NULL,
  /*CASE WHEN E.archived=1 THEN NOW() ELSE NULL END,*/
  NOW(),
  NOW()
  FROM bioproc_old.exams AS E
  JOIN bioproc_old.mybama_map AS M ON M.id <> "" AND E.created_by_id=CAST(M.id AS UNSIGNED)
  JOIN users AS U ON M.mybama_id=U.username
);

/*import signups*/

INSERT INTO signups (user_id,exam_id,deleted_at,created_at,updated_at)
(
  SELECT U.id,E.id,CASE WHEN P.approved=0 THEN NOW() ELSE NULL END,P.timestamp,NOW()
  FROM bioproc_old.proctors AS P
  JOIN exams AS E ON P.exam_id=E.prev_id
  JOIN bioproc_old.mybama_map AS M ON M.id <> "" AND P.user_id=CAST(M.id AS UNSIGNED)
  JOIN users AS U ON M.mybama_id=U.username
);

ALTER TABLE exams DROP COLUMN prev_id;
