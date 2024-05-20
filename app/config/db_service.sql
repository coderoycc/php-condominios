-- condominio_santa_cruz.dbo.tblServices definition

-- Drop table

-- DROP TABLE condominio_santa_cruz.dbo.tblServices;

CREATE TABLE condominio_santa_cruz.dbo.tblServices (
	id_service int IDENTITY(1,1) NOT NULL,
	service_name varchar(100) COLLATE Modern_Spanish_CI_AS NOT NULL,
	service_name_id int NULL,
	code varchar(80) COLLATE Modern_Spanish_CI_AS NOT NULL,
	user_id int NULL,
	department_id int NULL,
	CONSTRAINT PK_tblServices PRIMARY KEY (id_service),
	CONSTRAINT fk_service_department FOREIGN KEY (department_id) REFERENCES condominio_santa_cruz.dbo.tblDepartments(id_department),
	CONSTRAINT fk_service_user FOREIGN KEY (user_id) REFERENCES condominio_santa_cruz.dbo.tblUsers(id_user)
);