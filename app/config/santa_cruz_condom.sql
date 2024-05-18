/*
 Navicat Premium Data Transfer

 Source Server         : Local Laptop
 Source Server Type    : SQL Server
 Source Server Version : 16001000
 Source Host           : localhost:1433
 Source Catalog        : condominio_santa_cruz
 Source Schema         : dbo

 Target Server Type    : SQL Server
 Target Server Version : 16001000
 File Encoding         : 65001

 Date: 18/05/2024 13:01:03
*/


-- ----------------------------
-- Table structure for tblDepartments
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblDepartments]') AND type IN ('U'))
	DROP TABLE [dbo].[tblDepartments]
GO

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
-- Records of tblDepartments
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblDepartments] ON
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'1', N'1011', N'3', N'Sala de reuniones por dos')
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'2', N'1013', N'2', N'Dep2 ')
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'3', N'1101', N'8', N'Departamento ')
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'4', N'1102-A', N'3', N'Departamento compartido')
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'5', N'1102-B', N'1', N'Sala de juegos')
GO

INSERT INTO [dbo].[tblDepartments] ([id_department], [dep_number], [bedrooms], [description]) VALUES (N'6', N'1210', N'1', N'Oficina')
GO

SET IDENTITY_INSERT [dbo].[tblDepartments] OFF
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
  [user_id_target] int  NULL
)
GO

ALTER TABLE [dbo].[tblLockerContent] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of tblLockerContent
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblLockerContent] ON
GO

INSERT INTO [dbo].[tblLockerContent] ([id_content], [locker_id], [content], [received_at], [user_id_target]) VALUES (N'1', N'4', N'', N'2024-05-11 14:48:37.213', N'3')
GO

INSERT INTO [dbo].[tblLockerContent] ([id_content], [locker_id], [content], [received_at], [user_id_target]) VALUES (N'2', N'4', N'', N'2024-05-11 14:51:15.887', N'3')
GO

INSERT INTO [dbo].[tblLockerContent] ([id_content], [locker_id], [content], [received_at], [user_id_target]) VALUES (N'3', N'4', N'Rappi comida', N'2024-05-11 14:52:24.753', N'7')
GO

SET IDENTITY_INSERT [dbo].[tblLockerContent] OFF
GO


-- ----------------------------
-- Table structure for tblLockers
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblLockers]') AND type IN ('U'))
	DROP TABLE [dbo].[tblLockers]
GO

CREATE TABLE [dbo].[tblLockers] (
  [id_locker] int  IDENTITY(1,1) NOT NULL,
  [locker_number] int  NULL,
  [locker_status] bit DEFAULT 1 NULL,
  [type] varchar(40) COLLATE Modern_Spanish_CI_AS  NULL
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


-- ----------------------------
-- Records of tblLockers
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblLockers] ON
GO

INSERT INTO [dbo].[tblLockers] ([id_locker], [locker_number], [locker_status], [type]) VALUES (N'3', N'1', N'1', N'correspondencia')
GO

INSERT INTO [dbo].[tblLockers] ([id_locker], [locker_number], [locker_status], [type]) VALUES (N'4', N'2', N'0', N'todo')
GO

SET IDENTITY_INSERT [dbo].[tblLockers] OFF
GO


-- ----------------------------
-- Table structure for tblPayments
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblPayments]') AND type IN ('U'))
	DROP TABLE [dbo].[tblPayments]
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
-- Records of tblPayments
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblPayments] ON
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'2', N'BOB', N'90', N'Pago suscripcion Premium', N'QR', N'', N'050', N'3', N'050', NULL, N'0', N'2024-05-02 17:50:20.913', N'12882760', NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'3', N'BOB', N'90', N'Pago suscripcion Premium', N'QR', N'a574d0ee-3855-45e2-a143-d04f33f6642b', N'050', N'3', N'050', NULL, N'0', N'2024-05-02 17:52:18.437', N'12882760', NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'4', N'BOB', N'90', N'Pago suscripcion Premium', N'QR', N'9dbc7910-a51f-42d9-b618-fa9112d2c381', N'050', N'3', N'050', NULL, N'0', N'2024-05-06 12:44:53.367', N'12882760', NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'5', N'BOB', N'70', N'Pago suscripcion Standard', N'QR', N'f4b9b746-187f-41a9-b24b-1e91853bd6ec', N'050', N'15', N'050', NULL, N'1', N'2024-05-06 15:57:22.030', N'12882760', NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'6', N'BOB', N'140', N'Pago suscripcion standard', N'QR', N'9fff7910-a544-43d9-b218-da9012d2c381', NULL, NULL, NULL, NULL, N'1', N'2024-05-07 18:17:49.073', NULL, NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'8', N'BOB', N'90', N'Pago Suscripcion premium', N'QR', NULL, N'050', N'7', N'050', N'', N'1', N'2024-05-07 18:39:54.890', NULL, NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'9', N'BOB', N'90', N'Pago premium', N'QR', NULL, N'050', N'9', N'050', NULL, N'1', N'2024-05-07 18:42:03.480', NULL, NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'10', N'BOB', N'140', N'pago standard', N'QR', NULL, N'050', N'10', N'050', NULL, N'1', N'2024-05-07 18:42:48.613', NULL, NULL)
GO

INSERT INTO [dbo].[tblPayments] ([idPayment], [currency], [amount], [gloss], [type], [correlation_id], [serviceCode], [app_user_id], [bussinessCode], [transaction_response], [confirmed], [created_at], [id_qr], [expiration_qr]) VALUES (N'11', N'BOB', N'140', N'Pago standart 2', N'QR', NULL, N'050', N'11', N'050', NULL, N'1', N'2024-05-07 18:43:41.523', NULL, NULL)
GO

SET IDENTITY_INSERT [dbo].[tblPayments] OFF
GO


-- ----------------------------
-- Table structure for tblPaymentSubscriptions
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblPaymentSubscriptions]') AND type IN ('U'))
	DROP TABLE [dbo].[tblPaymentSubscriptions]
GO

CREATE TABLE [dbo].[tblPaymentSubscriptions] (
  [payment_id] int  NOT NULL,
  [subscription_id] int  NOT NULL
)
GO

ALTER TABLE [dbo].[tblPaymentSubscriptions] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of tblPaymentSubscriptions
-- ----------------------------
INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'5', N'8')
GO

INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'6', N'9')
GO

INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'8', N'10')
GO

INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'9', N'11')
GO

INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'10', N'12')
GO

INSERT INTO [dbo].[tblPaymentSubscriptions] ([payment_id], [subscription_id]) VALUES (N'11', N'13')
GO


-- ----------------------------
-- Table structure for tblResidents
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblResidents]') AND type IN ('U'))
	DROP TABLE [dbo].[tblResidents]
GO

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
-- Records of tblResidents
-- ----------------------------
INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'1', N'3', NULL, NULL)
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'3', N'5', NULL, NULL)
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'7', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'8', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'9', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'10', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'11', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'12', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'13', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'14', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'15', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'16', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'17', N'1', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'18', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'19', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'20', N'4', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'21', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'22', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'23', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'24', N'3', N'', N'')
GO

INSERT INTO [dbo].[tblResidents] ([user_id], [department_id], [phone], [details]) VALUES (N'25', N'3', N'', N'')
GO


-- ----------------------------
-- Table structure for tblSubscriptions
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblSubscriptions]') AND type IN ('U'))
	DROP TABLE [dbo].[tblSubscriptions]
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
-- Records of tblSubscriptions
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblSubscriptions] ON
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'1', N'1', N'2024-04-25 17:18:04.000', N'1', N'CHAMBI', N'1', N'44323246', N'4', N'2024-05-25 17:18:46.000', N'1', N'FFC09A', N'4')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'2', N'1', N'2024-05-02 13:47:22.753', N'1', N'Roberto Carlos', N'0', N'000', N'3', N'2024-01-06 23:59:59.000', N'1', N'AB6CB5', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'3', N'1', N'2024-05-02 14:09:17.950', N'3', N'Geminis', N'0', N'000', N'5', N'2024-06-01 23:59:59.000', N'1', N'DE6AB1', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'4', N'1', N'2024-05-02 14:33:09.037', N'3', N'Geminis', N'0', N'000', N'5', N'2024-06-01 23:59:59.000', N'1', N'508484', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'5', N'1', N'2024-05-02 14:39:14.820', N'3', N'Geminis', N'0', N'000', N'5', N'2024-06-01 23:59:59.000', N'1', N'2C815D', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'6', N'1', N'2024-05-06 12:38:20.287', N'15', N'test 3', N'0', N'000', N'3', N'2024-06-05 23:59:59.000', N'1', N'C4501F', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'7', N'1', N'2024-05-06 12:46:07.403', N'1', N'Roberto Carlos', N'0', N'000', N'3', N'2024-06-05 23:59:59.000', N'1', N'F626ED', N'1')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'8', N'2', N'2024-05-07 18:13:31.000', N'10', N'CALIZAYA', N'1', N'000', N'6', N'2024-05-21 18:14:08.000', N'1', N'FFC099', N'3')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'9', N'2', N'2024-05-07 18:14:49.000', N'13', N'OLMOS', N'2', N'10046643282', N'4', N'2024-06-04 18:15:22.000', N'0', N'DDE653', N'3')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'10', N'3', N'2024-05-07 18:25:33.650', N'12', NULL, N'1', N'000', N'5', N'2024-06-04 18:25:10.000', N'1', N'EEEFF3', N'3')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'11', N'3', N'2024-05-02 18:25:57.000', N'2', N'S/N', N'1', N'000', N'4', N'2024-11-21 18:26:27.000', N'1', N'FFE432', N'3')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'12', N'2', N'2024-05-07 18:27:41.380', N'26', N'PALERMO', N'2', N'10098892722', N'5', N'2024-05-07 18:27:25.000', N'1', N'FDFD32', N'3')
GO

INSERT INTO [dbo].[tblSubscriptions] ([id_subscription], [type_id], [subscribed_in], [paid_by], [paid_by_name], [period], [nit], [department_id], [expires_in], [valid], [code], [limit]) VALUES (N'13', N'2', N'2024-05-07 18:30:05.220', N'24', N'S/N', N'2', N'100025454854', N'2', N'2024-05-01 18:29:49.000', N'0', N'FDEED4', N'3')
GO

SET IDENTITY_INSERT [dbo].[tblSubscriptions] OFF
GO


-- ----------------------------
-- Table structure for tblSubscriptionType
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblSubscriptionType]') AND type IN ('U'))
	DROP TABLE [dbo].[tblSubscriptionType]
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
-- Records of tblSubscriptionType
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblSubscriptionType] ON
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'1', N'Gratuito', N'Comienzo fácil', N'1', N'0', N'0', N'Esta suscripción te permite disfrutar de la aplicación por un mes de forma gratuita.', N'2', N'0', N'["Casilleros para recibir pedidos","Casilla con Número Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificación y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de número de teléfono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad."]', N'0', N'0')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'2', N'Standard', N'Experiencia optima', N'1', N'0', N'400', N'Permite recibir avisos sobre correspondencia en los casilleros.', N'6', N'0', N'["Casilleros para recibir pedidos","Casilla con Número Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificación y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de número de teléfono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad.","Asistencia y soporte"]', N'1', N'500')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'3', N'Premium', N'Solución personalizada', N'1', N'1', N'750', N'Permite recibir avisos de correspondencia y tambien la posibilidad de pagar tus servicios.', N'6', N'0', N'["Beneficios de suscripción Standard","Pago de Servicios Básicos.","Notificación y reporte de Pago de Servicios online.","Sistema de pago mediante QR.","Recepción del baucher de Pago de Servicios vía correo electrónico."]', N'1', N'900')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price]) VALUES (N'8', N'Premium VIP', N'Solución avanzada', N'1', N'1', N'850', NULL, N'6', N'1', N'["Beneficios de suscripción Premium","Recepción de las Facturas físicas de Pago de Servicios mediante la Casilla Postal."]', N'1', N'990')
GO

SET IDENTITY_INSERT [dbo].[tblSubscriptionType] OFF
GO


-- ----------------------------
-- Table structure for tblUsers
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblUsers]') AND type IN ('U'))
	DROP TABLE [dbo].[tblUsers]
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
-- Records of tblUsers
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblUsers] ON
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'1', N'Roberto Carlos', N'Chambi Calizaya', N'betto', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'6763-ef43-saw32-e345', N'2024-04-24 12:13:50.300', N'79656767', N'M', N'0')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'2', N'Geremias', N'Local', N'dos', N'admin', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 11:09:43.587', N'78965478', N'F', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'3', N'Geminis', N'Looooo', N'77889944', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 11:32:13.030', N'77889944', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'7', N'Jorge Castellon', N'', N'79656767', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'14aba094-ec24-4312-bc39-3f8b5ffe2da8', N'2024-04-29 17:56:12.393', N'79656767', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'8', N'Pepe Juan Cerapio', N'', N'77889944', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 17:58:38.680', N'77889944', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'9', N'Pepe Juan Cerapio', N'', N'66554499', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 18:21:20.290', N'66554499', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'10', N'Rodrigo Ernesto Papillon', N'Jorge', N'66445555', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 18:25:10.903', N'66445555', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'11', N'Olivia Rodrigo', N'', N'66445559', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 18:28:42.450', N'66445559', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'12', N'Olivia Rodrigo', N'Jorge', N'11223344', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-29 18:29:01.987', N'11223344', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'13', N'test', N'', N'77889943', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-04-30 12:14:36.380', N'77889943', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'14', N'test', N'', N'77889942', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-04-30 12:15:53.817', N'77889942', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'15', N'test 3', N'', N'77777771', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'00-00-000', N'2024-04-30 12:19:38.200', N'77777771', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'16', N'test 3', N'', N'77777772', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'00-00-000', N'2024-04-30 12:27:31.740', N'77777772', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'17', N'Jorge El curioso', N'', N'77441122', N'resident', N'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3', N'00-00-000', N'2024-04-30 12:30:00.237', N'77441122', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'18', N'test 5', N'', N'77777773', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-04-30 13:29:09.500', N'77777773', N'F', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'19', N'test 6', N'', N'77777774', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'00-00-000', N'2024-04-30 13:36:22.420', N'77777774', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'20', N'Jiuseppe', N'', N'70578049', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-04-30 16:23:59.293', N'70578049', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'21', N'test 5', N'', N'77777775', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'00-00-000', N'2024-05-03 13:04:33.897', N'77777775', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'22', N'test 6', N'', N'77777776', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-05-03 17:35:41.397', N'77777776', N'F', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'23', N'test 7', N'', N'77777777', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-05-03 17:39:46.947', N'77777777', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'24', N'test 8', N'', N'77777778', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-05-04 12:44:23.997', N'77777778', N'F', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'25', N'test 9', N'', N'77777779', N'resident', N'0ffe1abd1a08215353c233d6e009613e95eec4253832a761af28ff37ac5a150c', N'71dbb0a1-962b-47b3-a235-e0dec3695925', N'2024-05-06 11:29:37.800', N'77777779', N'M', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'26', N'PEPITO PISTOLAS ', N'', N'pitillo', N'conserje', N'3bf70ca587b7a901f6366fe81de62fc6945d4a2cc48cb2bbbaf0280d0f0e5da4', N'00-00-000', N'2024-05-06 19:04:13.663', N'', N'O', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'28', N'Jorge Remedios', N'', N'jojito', N'admin', N'3ba8aff75f7baae13e371ed91b4502f413653ea210869257ec16b86a11f14ea9', N'', N'2024-05-11 11:47:17.070', N'', N'O', N'1')
GO

INSERT INTO [dbo].[tblUsers] ([id_user], [first_name], [last_name], [username], [role], [password], [device_id], [created_at], [cellphone], [gender], [status]) VALUES (N'29', N'Conseje Turno Mañana', N'', N'conse', N'conserje', N'd5487df084463bd5a479211bc50c2fa0dacb3f2fd4ee41e50612b3abe3061362', N'', N'2024-05-14 12:50:04.430', N'', N'O', N'1')
GO

SET IDENTITY_INSERT [dbo].[tblUsers] OFF
GO


-- ----------------------------
-- Table structure for tblUsersSubscribed
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblUsersSubscribed]') AND type IN ('U'))
	DROP TABLE [dbo].[tblUsersSubscribed]
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
-- Records of tblUsersSubscribed
-- ----------------------------
INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'1', N'1', NULL)
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'1', N'2', N'2024-05-02 13:47:22.760')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'7', N'1', N'2024-04-29 17:56:12.407')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'3', N'3', N'2024-05-02 14:09:17.950')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'3', N'4', N'2024-05-02 14:33:09.040')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'3', N'5', N'2024-05-02 14:39:14.823')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'15', N'6', N'2024-05-06 12:38:20.290')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'8', N'1', N'2024-04-29 17:58:38.693')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'12', N'1', N'2024-04-29 18:29:02.000')
GO

INSERT INTO [dbo].[tblUsersSubscribed] ([user_id], [subscription_id], [subscribed_in]) VALUES (N'1', N'7', N'2024-05-06 12:46:07.410')
GO


-- ----------------------------
-- Auto increment value for tblDepartments
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblDepartments]', RESEED, 7)
GO


-- ----------------------------
-- Primary Key structure for table tblDepartments
-- ----------------------------
ALTER TABLE [dbo].[tblDepartments] ADD CONSTRAINT [PK__tblDepar__0FC1D23F9FADF857] PRIMARY KEY CLUSTERED ([id_department])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblLockerContent
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblLockerContent]', RESEED, 3)
GO


-- ----------------------------
-- Primary Key structure for table tblLockerContent
-- ----------------------------
ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [PK__tblConte__3E703D5FEB697133] PRIMARY KEY CLUSTERED ([id_content])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblLockers
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblLockers]', RESEED, 4)
GO


-- ----------------------------
-- Primary Key structure for table tblLockers
-- ----------------------------
ALTER TABLE [dbo].[tblLockers] ADD CONSTRAINT [PK__tblLocke__4AB6F3DCAD211247] PRIMARY KEY CLUSTERED ([id_locker])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblPayments
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblPayments]', RESEED, 11)
GO


-- ----------------------------
-- Primary Key structure for table tblPayments
-- ----------------------------
ALTER TABLE [dbo].[tblPayments] ADD CONSTRAINT [PK__tblPayme__F22D4A45395F7596] PRIMARY KEY CLUSTERED ([idPayment])
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
-- Auto increment value for tblSubscriptions
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblSubscriptions]', RESEED, 13)
GO


-- ----------------------------
-- Primary Key structure for table tblSubscriptions
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptions] ADD CONSTRAINT [PK__tblSubsc__5F1F3D1503C2A7CB] PRIMARY KEY CLUSTERED ([id_subscription])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblSubscriptionType
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblSubscriptionType]', RESEED, 8)
GO


-- ----------------------------
-- Primary Key structure for table tblSubscriptionType
-- ----------------------------
ALTER TABLE [dbo].[tblSubscriptionType] ADD CONSTRAINT [PK__tblSubsc__5B00685110AB91CF] PRIMARY KEY CLUSTERED ([id_subscription_type])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblUsers
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblUsers]', RESEED, 29)
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
ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_locker_content] FOREIGN KEY ([locker_id]) REFERENCES [dbo].[tblLockers] ([id_locker]) ON DELETE CASCADE ON UPDATE NO ACTION
GO

ALTER TABLE [dbo].[tblLockerContent] ADD CONSTRAINT [fk_user_target_content] FOREIGN KEY ([user_id_target]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
GO


-- ----------------------------
-- Foreign Keys structure for table tblPayments
-- ----------------------------
ALTER TABLE [dbo].[tblPayments] ADD CONSTRAINT [fk_user_payment] FOREIGN KEY ([app_user_id]) REFERENCES [dbo].[tblUsers] ([id_user]) ON DELETE CASCADE ON UPDATE NO ACTION
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

