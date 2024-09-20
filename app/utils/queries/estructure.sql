
CREATE TABLE [dbo].[tblDepartments] (
  [id_department] int  IDENTITY(1,1) NOT NULL,
  [dep_number] varchar(10) COLLATE Modern_Spanish_CI_AS  NULL,
  [bedrooms] int  NULL,
  [description] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL
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


-- ----------------------------
-- Table structure for tblLockerContent
-- ----------------------------
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


-- ----------------------------
-- Table structure for tblLockers
-- ----------------------------
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


-- ----------------------------
-- Table structure for tblPayments
-- ----------------------------
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
  [expiration_qr] datetime  NULL
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


-- ----------------------------
-- Table structure for tblPaymentShipping
-- ----------------------------

CREATE TABLE [dbo].[tblPaymentShipping] (
  [payment_id] int  NOT NULL,
  [shipping_id] int  NOT NULL
)
GO

ALTER TABLE [dbo].[tblPaymentShipping] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Table structure for tblPaymentsServices
-- ----------------------------
CREATE TABLE [dbo].[tblPaymentsServices] (
  [id_payment_service] int  IDENTITY(1,1) NOT NULL,
  [payment_id] int  NULL,
  [department_id] int  NULL,
  [target_date] date  NULL,
  [status] bit  NULL
)
GO

ALTER TABLE [dbo].[tblPaymentsServices] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'Del mes que se pago (GENERAR QR)',
'SCHEMA', N'dbo',
'TABLE', N'tblPaymentsServices',
'COLUMN', N'target_date'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: Aun no pagado por administrador, 1: pagado por administrador',
'SCHEMA', N'dbo',
'TABLE', N'tblPaymentsServices',
'COLUMN', N'status'
GO


-- ----------------------------
-- Table structure for tblPaymentSubscriptions
-- ----------------------------
CREATE TABLE [dbo].[tblPaymentSubscriptions] (
  [payment_id] int  NOT NULL,
  [subscription_id] int  NOT NULL
)
GO

ALTER TABLE [dbo].[tblPaymentSubscriptions] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Table structure for tblResidents
-- ----------------------------
CREATE TABLE [dbo].[tblResidents] (
  [user_id] int  NOT NULL,
  [department_id] int  NULL,
  [phone] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [details] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL
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
'MS_Description', N'Detalles objservaciones',
'SCHEMA', N'dbo',
'TABLE', N'tblResidents',
'COLUMN', N'details'
GO


-- ----------------------------
-- Table structure for tblServiceDetail
-- ----------------------------
CREATE TABLE [dbo].[tblServiceDetail] (
  [id_service_detail] int  IDENTITY(1,1) NOT NULL,
  [service_id] int  NULL,
  [amount] float(53)  NULL,
  [month] date  NULL
)
GO

ALTER TABLE [dbo].[tblServiceDetail] SET (LOCK_ESCALATION = TABLE)
GO
--**separation
-- ----------------------------
-- Table structure for tblServices
-- ----------------------------
CREATE TABLE [dbo].[tblServices] (
  [id_service] int  IDENTITY(1,1) NOT NULL,
  [service_name] varchar(100) COLLATE Modern_Spanish_CI_AS  NOT NULL,
  [service_name_id] int  NULL,
  [code] varchar(80) COLLATE Modern_Spanish_CI_AS  NOT NULL,
  [user_id] int  NULL,
  [department_id] int  NULL
)
GO

ALTER TABLE [dbo].[tblServices] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Table structure for tblShipping
-- ----------------------------
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


-- ----------------------------
-- Table structure for tblSubscriptions
-- ----------------------------
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
  [valid] int  NULL,
  [code] varchar(6) COLLATE Modern_Spanish_CI_AS  NULL,
  [limit] int  NULL
)
GO

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
'MS_Description', N'confirmacion transaccion ----> 0: no, 1: si',
'SCHEMA', N'dbo',
'TABLE', N'tblSubscriptions',
'COLUMN', N'valid'
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


-- ----------------------------
-- Table structure for tblSubscriptionType
-- ----------------------------
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
  [annual_price] float(53)  NULL
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


-- ----------------------------
-- Table structure for tblUsers
-- ----------------------------
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
  [status] int  NULL
)
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


-- ----------------------------
-- Table structure for tblUsersSubscribed
-- ----------------------------
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
-- Primary Key structure for table tblPayments
-- ----------------------------
ALTER TABLE [dbo].[tblPayments] ADD CONSTRAINT [PK__tblPayme__F22D4A45395F7596] PRIMARY KEY CLUSTERED ([idPayment])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Uniques structure for table tblPaymentsServices
-- ----------------------------
ALTER TABLE [dbo].[tblPaymentsServices] ADD CONSTRAINT [uk_department_date] UNIQUE NONCLUSTERED ([target_date] ASC, [department_id] ASC)
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
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
-- Primary Key structure for table tblServiceDetail
-- ----------------------------
ALTER TABLE [dbo].[tblServiceDetail] ADD CONSTRAINT [PK__tblServi__6837C632047D3C99] PRIMARY KEY CLUSTERED ([id_service_detail])
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
ALTER TABLE [dbo].[tblPaymentsServices] ADD CONSTRAINT [fk_payment_department] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE NO ACTION ON UPDATE NO ACTION
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
-- Foreign Keys structure for table tblServiceDetail
-- ----------------------------
ALTER TABLE [dbo].[tblServiceDetail] ADD CONSTRAINT [fk_service_details] FOREIGN KEY ([service_id]) REFERENCES [dbo].[tblServices] ([id_service]) ON DELETE NO ACTION ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblServices
-- ----------------------------
ALTER TABLE [dbo].[tblServices] ADD CONSTRAINT [fk_service_department] FOREIGN KEY ([department_id]) REFERENCES [dbo].[tblDepartments] ([id_department]) ON DELETE NO ACTION ON UPDATE NO ACTION
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

-- DATA SEED TYPE SUBSCRIPTION

INSERT INTO [dbo].[tblSubscriptionType] ([name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'Gratuito', N'Comienzo fácil', N'1', N'0', N'0', N'Esta suscripción te permite disfrutar de la aplicación por un mes de forma gratuita.', N'2', N'0', N'["Casilleros para recibir pedidos","Casilla con Número Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificación y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de número de teléfono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad."]', N'0', N'0')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'Standard', N'Experiencia optima', N'1', N'0', N'400', N'Permite recibir avisos sobre correspondencia en los casilleros.', N'6', N'0', N'["Casilleros para recibir pedidos","Casilla con Número Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificación y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de número de teléfono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad.","Asistencia y soporte"]', N'1', N'500')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'Premium', N'Solución personalizada', N'1', N'1', N'750', N'Permite recibir avisos de correspondencia y tambien la posibilidad de pagar tus servicios.', N'6', N'0', N'["Beneficios de suscripción Standard","Pago de Servicios Básicos.","Notificación y reporte de Pago de Servicios online.","Sistema de pago mediante QR.","Recepción del baucher de Pago de Servicios vía correo electrónico."]', N'1', N'900')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'Premium VIP', N'Solución avanzada', N'1', N'1', N'850', N'', N'6', N'1', N'["Beneficios de suscripción Premium","Recepción de las Facturas físicas de Pago de Servicios mediante la Casilla Postal."]', N'1', N'990')
GO


-- DATA USER ADMIN;
INSERT INTO [dbo].[tblUsers] ([first_name], [last_name], [username], [password], [role], [device_id], [cellphone], [gender], [status]) VALUES (N'Admin', N'Admin', N'admin', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'admin', N'00-00-000', N'000',N'O', N'1')
GO

