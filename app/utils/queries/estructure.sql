
CREATE TABLE [dbo].[tblDepartments] (
  [id_department] int  IDENTITY(1,1) NOT NULL,
  [dep_number] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [bedrooms] int  NULL,
  [description] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [status] bit DEFAULT 1 NULL
)
GO

ALTER TABLE [dbo].[tblDepartments] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Numero de departamento',
'SCHEMA', N'dbo',
'TABLE', N'tblDepartments',
'COLUMN', N'dep_number'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Numero de habitaciones',
'SCHEMA', N'dbo',
'TABLE', N'tblDepartments',
'COLUMN', N'bedrooms'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: Inactivo, 1: Activo',
'SCHEMA', N'dbo',
'TABLE', N'tblDepartments',
'COLUMN', N'status'
GO


-- ----------------------------
-- Table structure for tblLockerContent
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblLockerContent]') AND type IN ('U'))
	DROP TABLE [dbo].[tblLockerContent]
GO

CREATE TABLE [dbo].[tblLockerContent] (
  [id_content] int  IDENTITY(1,1) NOT NULL,
  [locker_id] int  NOT NULL,
  [content] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [received_at] datetime DEFAULT getdate() NULL,
  [user_id_target] int  NULL,
  [department_id] int  NULL,
  [received_by] int  NULL,
  [delivered] bit DEFAULT 0 NULL,
  [shipping_id] int  NULL
)
GO

ALTER TABLE [dbo].[tblLockerContent] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario target de origen o destino',
'SCHEMA', N'dbo',
'TABLE', N'tblLockerContent',
'COLUMN', N'user_id_target'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Departamento target (user_id_target)',
'SCHEMA', N'dbo',
'TABLE', N'tblLockerContent',
'COLUMN', N'department_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario (conserje)',
'SCHEMA', N'dbo',
'TABLE', N'tblLockerContent',
'COLUMN', N'received_by'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: NO ENTREGADO 1: ENTREGADO',
'SCHEMA', N'dbo',
'TABLE', N'tblLockerContent',
'COLUMN', N'delivered'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Cuando es un envio ',
'SCHEMA', N'dbo',
'TABLE', N'tblLockerContent',
'COLUMN', N'shipping_id'
GO

CREATE TABLE [dbo].[tblLockers] (
  [id_locker] int  IDENTITY(1,1) NOT NULL,
  [locker_number] int  NULL,
  [locker_status] bit DEFAULT 1 NULL,
  [type] varchar(40) COLLATE Modern_Spanish_CI_AS  NULL,
  [in_out] varchar(30) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblLockers] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: ocupado, 1: libre',
'SCHEMA', N'dbo',
'TABLE', N'tblLockers',
'COLUMN', N'locker_status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'todo | correspondencia',
'SCHEMA', N'dbo',
'TABLE', N'tblLockers',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'ENTRADA | SALIDA',
'SCHEMA', N'dbo',
'TABLE', N'tblLockers',
'COLUMN', N'in_out'
GO

CREATE TABLE [dbo].[tblPayments] (
  [idPayment] int  IDENTITY(1,1) NOT NULL,
  [currency] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [amount] float(53)  NULL,
  [gloss] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [type] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [correlation_id] varchar(128) COLLATE Modern_Spanish_CI_AS  NULL,
  [serviceCode] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [app_user_id] int  NULL,
  [bussinessCode] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [transaction_response] varchar(2048) COLLATE Modern_Spanish_CI_AS  NULL,
  [confirmed] bit DEFAULT 0 NULL,
  [created_at] datetime DEFAULT getdate() NULL,
  [id_qr] int  NULL,
  [expiration_qr] datetime  NULL,
  [account] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblPayments] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Tipo de moneda BOB USD',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'currency'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Glosa del pago',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'gloss'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Tipo de pago: QR | Deposito | CHEQUE ETC',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'type'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Identificador unico',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'correlation_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'QR: 050 | 040',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'serviceCode'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Id Usuario',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'app_user_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Codigo de la empresa',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'bussinessCode'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no, 1: si',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'confirmed'
GO

EXEC sp_addextendedproperty
'MS_Description', N'En caso de ser qr, aqui se almacena el id del qr',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'id_qr'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Fecha de expiracion del QR',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'expiration_qr'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Relacion con una cuenta',
'SCHEMA', N'dbo',
'TABLE', N'tblPayments',
'COLUMN', N'account'
GO

CREATE TABLE [dbo].[tblPaymentShipping] (
  [payment_id] int  NOT NULL,
  [shipping_id] int  NOT NULL
)
GO

ALTER TABLE [dbo].[tblPaymentShipping] SET (LOCK_ESCALATION = TABLE)
GO

CREATE TABLE [dbo].[tblPaymentsServices] (
  [id_payment_service] int  IDENTITY(1,1) NOT NULL,
  [payment_id] int  NULL,
  [subscription_id] int  NULL,
  [month] int  NULL,
  [year] int  NULL,
  [status] varchar(100) COLLATE Modern_Spanish_CI_AS DEFAULT 'PENDIENTE' NULL
)
GO

ALTER TABLE [dbo].[tblPaymentsServices] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'PENDIENTE: generado por el usuario, QR PAGADO: pagado por el residente, PAGADO: pagado por el administrador',
'SCHEMA', N'dbo',
'TABLE', N'tblPaymentsServices',
'COLUMN', N'status'
GO

CREATE TABLE [dbo].[tblPaymentSubscriptions] (
  [payment_id] int  NOT NULL,
  [subscription_id] int  NOT NULL
)
GO

ALTER TABLE [dbo].[tblPaymentSubscriptions] SET (LOCK_ESCALATION = TABLE)
GO

CREATE TABLE [dbo].[tblResidents] (
  [user_id] int  NOT NULL,
  [department_id] int  NULL,
  [phone] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [email] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblResidents] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Telefono fijo',
'SCHEMA', N'dbo',
'TABLE', N'tblResidents',
'COLUMN', N'phone'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Correo del residente',
'SCHEMA', N'dbo',
'TABLE', N'tblResidents',
'COLUMN', N'email'
GO

CREATE TABLE [dbo].[tblServiceDetailPerMonth] (
  [id_service_detail] int  IDENTITY(1,1) NOT NULL,
  [service_id] int  NULL,
  [amount] float(53)  NULL,
  [month] int  NULL,
  [year] int  NULL,
  [filename] varchar(200) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblServiceDetailPerMonth] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Mes correspondiente ',
'SCHEMA', N'dbo',
'TABLE', N'tblServiceDetailPerMonth',
'COLUMN', N'month'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Nombre del Archivo del comprobante',
'SCHEMA', N'dbo',
'TABLE', N'tblServiceDetailPerMonth',
'COLUMN', N'filename'
GO

CREATE TABLE [dbo].[tblServices] (
  [id_service] int  IDENTITY(1,1) NOT NULL,
  [service_name] varchar(100) COLLATE Modern_Spanish_CI_AS  NOT NULL,
  [service_name_id] int  NULL,
  [code] varchar(80) COLLATE Modern_Spanish_CI_AS  NOT NULL,
  [user_id] int  NULL,
  [subscription_id] int  NULL
)
GO

ALTER TABLE [dbo].[tblServices] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario residente que crea el servicio',
'SCHEMA', N'dbo',
'TABLE', N'tblServices',
'COLUMN', N'user_id'
GO

CREATE TABLE [dbo].[tblShipping] (
  [id] int  IDENTITY(1,1) NOT NULL,
  [name_origin] varchar(200) COLLATE Modern_Spanish_CI_AS  NULL,
  [country_origin] varchar(120) COLLATE Modern_Spanish_CI_AS  NULL,
  [address_origin] varchar(250) COLLATE Modern_Spanish_CI_AS  NULL,
  [postal_code_origin] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [city_origin] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [nif_origin] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [name_destiny] varchar(200) COLLATE Modern_Spanish_CI_AS  NULL,
  [country_destiny] varchar(120) COLLATE Modern_Spanish_CI_AS  NULL,
  [address_destiny] varchar(250) COLLATE Modern_Spanish_CI_AS  NULL,
  [postal_code_destiny] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [city_destiny] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [nif_destiny] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [weight] float(53)  NULL,
  [h] float(53)  NULL,
  [l] float(53)  NULL,
  [w] float(53)  NULL,
  [price] float(53)  NULL,
  [currency] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [tracking_id] varchar(25) COLLATE Modern_Spanish_CI_AS  NULL,
  [status] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL,
  [department_id] int  NULL,
  [created_by] int  NULL,
  [created_at] datetime DEFAULT getdate() NULL,
  [nat] bit DEFAULT 0 NULL,
  [phone_origin] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL,
  [phone_destiny] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL,
  [company] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL,
  [volume] float(53)  NULL,
  [envelope] bit  NULL
)
GO

ALTER TABLE [dbo].[tblShipping] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Peso KILOS',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'weight'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Altura cm',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'h'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Largo cm',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'l'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Ancho cm',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'w'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Precio de envio',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Moneda',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'currency'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Numero de seguimiento (guia)',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'tracking_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Estado del envio PENDIENTE | EN PROCESO | SIN PAGAR | PARA ENVIAR | ENVIADO',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Usuario Residente propietario envio',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'created_by'
GO

EXEC sp_addextendedproperty
'MS_Description', N'1: nacional, 0: internacional',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'nat'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Empresa de envio',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'company'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Volumen del envio',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'volume'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no es sobre, 1: es sobre',
'SCHEMA', N'dbo',
'TABLE', N'tblShipping',
'COLUMN', N'envelope'
GO

CREATE TABLE [dbo].[tblSubscriptionCompany] (
  [subscription_id] int  NULL,
  [subscribe_in] datetime DEFAULT getdate() NULL,
  [company] varchar(250) COLLATE Modern_Spanish_CI_AS  NULL,
  [company_id] int  NULL
)
GO

ALTER TABLE [dbo].[tblSubscriptionCompany] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'id de la suscripcion de la APP',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionCompany',
'COLUMN', N'subscription_id'
GO

EXEC sp_addextendedproperty
'MS_Description', N'empresa a la que suscribio',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionCompany',
'COLUMN', N'company'
GO

CREATE TABLE [dbo].[tblSubscriptions] (
  [id_subscription] int  IDENTITY(1,1) NOT NULL,
  [type_id] int  NOT NULL,
  [subscribed_in] datetime DEFAULT getdate() NULL,
  [paid_by] int  NULL,
  [paid_by_name] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [period] int  NULL,
  [nit] varchar(40) COLLATE Modern_Spanish_CI_AS  NULL,
  [department_id] int  NULL,
  [expires_in] datetime  NULL,
  [code] varchar(6) COLLATE Modern_Spanish_CI_AS  NULL,
  [limit] int  NULL,
  [status] varchar(30) COLLATE Modern_Spanish_CI_AS DEFAULT '' NULL,
  [suspended_in] date  NULL
)
GO
--**separation
ALTER TABLE [dbo].[tblSubscriptions] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'User ID (resident)',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'paid_by'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Periodo de 6 meses 1 = 6 meses, 2 = 12 meses',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'period'
GO

EXEC sp_addextendedproperty
'MS_Description', N'CODIGO autogenerado',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'code'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Limite de usuarios que pueden usar esta suscripcion Default = 3',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'limit'
GO

EXEC sp_addextendedproperty
'MS_Description', N' VALIDO | SUSPENDIDO | ELIMINADO ',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Fecha que se suspendio la suscripcion',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'suspended_in'
GO

CREATE TABLE [dbo].[tblSubscriptionType] (
  [id_subscription_type] int  IDENTITY(1,1) NOT NULL,
  [name] varchar(200) COLLATE Modern_Spanish_CI_AS  NULL,
  [tag] varchar(80) COLLATE Modern_Spanish_CI_AS  NULL,
  [see_lockers] bit  NULL,
  [see_services] bit  NULL,
  [price] float(53)  NULL,
  [description] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [months_duration] int  NULL,
  [courrier] bit DEFAULT 0 NULL,
  [details] varchar(2048) COLLATE Modern_Spanish_CI_AS  NULL,
  [iva] bit  NULL,
  [annual_price] float(53)  NULL,
  [status] int  NULL,
  [max_users] int  NULL
)
GO

ALTER TABLE [dbo].[tblSubscriptionType] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no, 1: si',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'see_lockers'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no, 1: si',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'see_services'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Precio base x 6 meses',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Meses de duracion de la suscription base minimo',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'months_duration'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no, 1 : si',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'courrier'
GO

EXEC sp_addextendedproperty
'MS_Description', N'JSON detalles',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'details'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: no incluye iva, 1: incluye iva',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'iva'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Precio x 12 meses',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'annual_price'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: inactivo, 1:activo',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Cantidad de usuarios maximos que se pueden pertenecer a este tipo de suscripcion',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptionType',
'COLUMN', N'max_users'
GO

CREATE TABLE [dbo].[tblUsers] (
  [id_user] int  IDENTITY(1,1) NOT NULL,
  [first_name] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [last_name] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [username] varchar(120) COLLATE Modern_Spanish_CI_AS  NULL,
  [role] varchar(30) COLLATE Modern_Spanish_CI_AS  NULL,
  [password] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [device_id] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [created_at] datetime DEFAULT getdate() NULL,
  [cellphone] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [gender] varchar(1) COLLATE Modern_Spanish_CI_AS  NULL,
  [status] int  NULL,
  [photo] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [assigned_code] bit DEFAULT 0 NULL,
  [device_code] varchar(36) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

INSERT INTO [dbo].[tblUsers](first_name, last_name, username, role, password, device_id, cellphone, gender, status) VALUES ('Administrador', 'master', 'master', 'admin', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '000-00-000', '0000000', 'M', 1);
GO
INSERT INTO [dbo].[tblUsers](first_name, last_name, username, role, password, device_id, cellphone, gender, status) VALUES ('Conserje', 'default', '10000', 'conserje', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', '000-00-000', '0000000', 'O', 1);
GO

ALTER TABLE [dbo].[tblUsers] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'F: Femenino, M:Masculino, O: otro',
'SCHEMA', N'dbo',
'TABLE', N'tblUsers',
'COLUMN', N'gender'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: down, 1: up',
'SCHEMA', N'dbo',
'TABLE', N'tblUsers',
'COLUMN', N'status'
GO

EXEC sp_addextendedproperty
'MS_Description', N'ruta + Nombre del archivo foto',
'SCHEMA', N'dbo',
'TABLE', N'tblUsers',
'COLUMN', N'photo'
GO

CREATE TABLE [dbo].[tblUsersSubscribed] (
  [user_id] int  NOT NULL,
  [subscription_id] int  NOT NULL,
  [subscribed_in] datetime DEFAULT getdate() NULL
)
GO

ALTER TABLE [dbo].[tblUsersSubscribed] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Primary Key structure for table tblDepartments
-- ----------------------------
ALTER TABLE [dbo].[tblDepartments] ADD CONSTRAINT [PK__tblDepar__0FC1D23F9FADF857] PRIMARY KEY CLUSTERED ([id_department])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblLockerContent
-- ----------------------------
ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [PK__tblConte__3E703D5FEB697133] PRIMARY KEY CLUSTERED ([id_content])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO

-- ----------------------------
-- Primary Key structure for table tblLockers
-- ----------------------------
ALTER TABLE [dbo].[tblLockers] ADD CONSTRAINT [PK__tblLocke__4AB6F3DCAD211247] PRIMARY KEY CLUSTERED ([id_locker])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Indexes structure for table tblPayments
-- ----------------------------
CREATE NONCLUSTERED INDEX [idx_account_payment]
ON [dbo].[tblPayments] (
  [account] ASC
)
GO


-- ----------------------------
-- Primary Key structure for table tblPayments
-- ----------------------------
ALTER TABLE [dbo].[tblPayments] ADD CONSTRAINT [PK__tblPayme__F22D4A45395F7596] PRIMARY KEY CLUSTERED ([idPayment])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Indexes structure for table tblPaymentsServices
-- ----------------------------
CREATE NONCLUSTERED INDEX [idx_month_year_pay_service]
ON [dbo].[tblPaymentsServices] (
  [month] ASC,
  [year] DESC
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'index del mes y año del pago',
'SCHEMA', N'dbo',
'TABLE', N'tblPaymentsServices',
'INDEX', N'idx_month_year_pay_service'
GO

-- ----------------------
-- tblUserTokenEvents
-- ---------------------
CREATE TABLE [dbo].[tblUserTokenEvents] (
  [id] int IDENTITY(1,1) NOT NULL,
  [token] varchar(64) NULL,
  [created_at] datetime DEFAULT GETDATE() NULL,
  [expires_at] datetime NULL,
  [user_id] int NULL,
  [used] bit DEFAULT 0 NULL,
  [event] varchar(128) NULL,
  PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON),
  CONSTRAINT [fk_user_token_event] FOREIGN KEY ([user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE CASCADE
)
GO

ALTER TABLE [dbo].[tblUserTokenEvents] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'TOKEN o CODIGO',
'SCHEMA', N'dbo',
'TABLE', N'tblUserTokenEvents',
'COLUMN', N'token'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: NO usado | 1: usado',
'SCHEMA', N'dbo',
'TABLE', N'tblUserTokenEvents',
'COLUMN', N'used'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Nombre del evento',
'SCHEMA', N'dbo',
'TABLE', N'tblUserTokenEvents',
'COLUMN', N'event'
GO

-- ----------------------------
-- Primary Key structure for table tblPaymentsServices
-- ----------------------------
ALTER TABLE [dbo].[tblPaymentsServices] ADD CONSTRAINT [PK__tblPayme__D791065B242314E1] PRIMARY KEY CLUSTERED ([id_payment_service])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblResidents
-- ----------------------------
ALTER TABLE [dbo].[tblResidents] ADD CONSTRAINT [PK__tblResid__B9BE370F1F66A3B7] PRIMARY KEY CLUSTERED ([user_id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Indexes structure for table tblServiceDetailPerMonth
-- ----------------------------
CREATE NONCLUSTERED INDEX [idx_month_year_detail]
ON [dbo].[tblServiceDetailPerMonth] (
  [month] ASC,
  [year] DESC
)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Indice para busqueda',
'SCHEMA', N'dbo',
'TABLE', N'tblServiceDetailPerMonth',
'INDEX', N'idx_month_year_detail'
GO


-- ----------------------------
-- Uniques structure for table tblServiceDetailPerMonth
-- ----------------------------
ALTER TABLE [dbo].[tblServiceDetailPerMonth] ADD CONSTRAINT [idx_uq_month_year_detail] UNIQUE NONCLUSTERED ([month] ASC, [year] DESC, [service_id] ASC)
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO

EXEC sp_addextendedproperty
'MS_Description', N'Index unico para mes año y servicio',
'SCHEMA', N'dbo',
'TABLE', N'tblServiceDetailPerMonth',
'CONSTRAINT', N'idx_uq_month_year_detail'
GO


-- ----------------------------
-- Primary Key structure for table tblServiceDetailPerMonth
-- ----------------------------
ALTER TABLE [dbo].[tblServiceDetailPerMonth] ADD CONSTRAINT [PK__tblServi__6837C632047D3C99] PRIMARY KEY CLUSTERED ([id_service_detail])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblServices
-- ----------------------------
ALTER TABLE [dbo].[tblServices] ADD CONSTRAINT [PK_tblServices] PRIMARY KEY CLUSTERED ([id_service])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblShipping
-- ----------------------------
ALTER TABLE [dbo].[tblShipping] ADD CONSTRAINT [PK__tblShipp__3213E83F4481393B] PRIMARY KEY CLUSTERED ([id])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Indexes structure for table tblSubscriptionCompany
-- ----------------------------
CREATE NONCLUSTERED INDEX [idx_company_external]
ON [dbo].[tblSubscriptionCompany] (
  [company_id] ASC
)
GO


-- ----------------------------
-- Primary Key structure for table tblSubscriptions
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptions] ADD CONSTRAINT [PK__tblSubsc__5F1F3D1503C2A7CB] PRIMARY KEY CLUSTERED ([id_subscription])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblSubscriptionType
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptionType] ADD CONSTRAINT [PK__tblSubsc__5B00685110AB91CF] PRIMARY KEY CLUSTERED ([id_subscription_type])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Primary Key structure for table tblUsers
-- ----------------------------
ALTER TABLE [dbo].[tblUsers] ADD CONSTRAINT [PK__tblUsers__D2D146371CAB04BD] PRIMARY KEY CLUSTERED ([id_user])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Foreign Keys structure for table tblLockerContent
-- ----------------------------
ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_department_target] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_locker_received_by] FOREIGN KEY ([received_by]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_locker_content] FOREIGN KEY ([locker_id]) REFERENCES [dbo].[tblLockers] ([id_locker]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_user_target_content] FOREIGN KEY ([user_id_target]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_shipping_content] FOREIGN KEY ([shipping_id]) REFERENCES [dbo].[tblShipping] ([id]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblPayments
-- ----------------------------
ALTER TABLE [dbo].[tblPayments] ADD CONSTRAINT [fk_user_payment] FOREIGN KEY ([app_user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblPaymentShipping
-- ----------------------------
ALTER TABLE [dbo].[tblPaymentShipping] ADD CONSTRAINT [fk_payment_shipping] FOREIGN KEY ([payment_id]) REFERENCES [dbo].[tblPayments] ([idPayment]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblPaymentShipping] ADD CONSTRAINT [fk_shipping_shipping] FOREIGN KEY ([shipping_id]) REFERENCES [dbo].[tblShipping] ([id]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblPaymentsServices
-- ----------------------------
ALTER TABLE [dbo].[tblPaymentsServices] ADD CONSTRAINT [fk_services_pay_sub] FOREIGN KEY ([subscription_id]) REFERENCES [dbo].[tblSubscriptions] ([id_subscription]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblPaymentsServices] ADD CONSTRAINT [fk_payment_payment] FOREIGN KEY ([payment_id]) REFERENCES [dbo].[tblPayments] ([idPayment]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblPaymentSubscriptions
-- ----------------------------
ALTER TABLE [dbo].[tblPaymentSubscriptions] ADD CONSTRAINT [fk_subscription_payment] FOREIGN KEY ([payment_id]) REFERENCES [dbo].[tblPayments] ([idPayment]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblPaymentSubscriptions] ADD CONSTRAINT [fk_subscription_subscription] FOREIGN KEY ([subscription_id]) REFERENCES [dbo].[tblSubscriptions] ([id_subscription]) ON DELETE CASCADE ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblResidents
-- ----------------------------
ALTER TABLE [dbo].[tblResidents] ADD CONSTRAINT [fk_user_resident] FOREIGN KEY ([user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblResidents] ADD CONSTRAINT [fk_department_resident] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblServiceDetailPerMonth
-- ----------------------------
ALTER TABLE [dbo].[tblServiceDetailPerMonth] ADD CONSTRAINT [fk_service_details] FOREIGN KEY ([service_id]) REFERENCES [dbo].[tblServices] ([id_service]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblServices
-- ----------------------------
ALTER TABLE [dbo].[tblServices] ADD CONSTRAINT [fk_service_subscirption] FOREIGN KEY ([subscription_id]) REFERENCES [dbo].[tblSubscriptions] ([id_subscription]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblServices] ADD CONSTRAINT [fk_service_user] FOREIGN KEY ([user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblShipping
-- ----------------------------
ALTER TABLE [dbo].[tblShipping] ADD CONSTRAINT [fk_department_shipping] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblShipping] ADD CONSTRAINT [fk_resident_shipping] FOREIGN KEY ([created_by]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblSubscriptionCompany
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptionCompany] ADD CONSTRAINT [fk_companieSubscription_subcription] FOREIGN KEY ([subscription_id]) REFERENCES [dbo].[tblSubscriptions] ([id_subscription]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblSubscriptions
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptions] ADD CONSTRAINT [fk_type_subscription] FOREIGN KEY ([type_id]) REFERENCES [dbo].[tblSubscriptionType] ([id_subscription_type]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblSubscriptions] ADD CONSTRAINT [fk_department_subscripction] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblSubscriptions] ADD CONSTRAINT [fk_resident_subscription] FOREIGN KEY ([paid_by]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblUsersSubscribed
-- ----------------------------
ALTER TABLE [dbo].[tblUsersSubscribed] ADD CONSTRAINT [fk_user_subscribed] FOREIGN KEY ([user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblUsersSubscribed] ADD CONSTRAINT [fk_subscription_data] FOREIGN KEY ([subscription_id]) REFERENCES [dbo].[tblSubscriptions] ([id_subscription]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

