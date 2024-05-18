/*
 Navicat Premium Data Transfer

 Source Server         : Local Laptop
 Source Server Type    : SQL Server
 Source Server Version : 16001000
 Source Host           : localhost:1433
 Source Catalog        : condominios_master
 Source Schema         : dbo

 Target Server Type    : SQL Server
 Target Server Version : 16001000
 File Encoding         : 65001

 Date: 18/05/2024 13:00:38
*/


-- ----------------------------
-- Table structure for tblCondominiosData
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblCondominiosData]') AND type IN ('U'))
	DROP TABLE [dbo].[tblCondominiosData]
GO

CREATE TABLE [dbo].[tblCondominiosData] (
  [idCondominio] int  IDENTITY(1,1) NOT NULL,
  [name] varchar(150) COLLATE Modern_Spanish_CI_AS  NULL,
  [pin] varchar(50) COLLATE Modern_Spanish_CI_AS  NULL,
  [dbname] varchar(100) COLLATE Modern_Spanish_CI_AS  NULL,
  [address] varchar(150) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblCondominiosData] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of tblCondominiosData
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblCondominiosData] ON
GO

INSERT INTO [dbo].[tblCondominiosData] ([idCondominio], [name], [pin], [dbname], [address]) VALUES (N'1', N'Barcelona 1', N'bar1', N'condominio_santa_cruz', N'Av. Mariscal Santa Cruz')
GO

INSERT INTO [dbo].[tblCondominiosData] ([idCondominio], [name], [pin], [dbname], [address]) VALUES (N'2', N'Barcelona 2', N'bar2', N'condominio_peru', N'Zona Fernandez Alonso')
GO

INSERT INTO [dbo].[tblCondominiosData] ([idCondominio], [name], [pin], [dbname], [address]) VALUES (N'3', N'Coroico', N'coco', N'condominio_coroico', N'Coroico plaza Bicentenario N* 189')
GO

INSERT INTO [dbo].[tblCondominiosData] ([idCondominio], [name], [pin], [dbname], [address]) VALUES (N'4', N'El Alto', N'xd', N'condominio_alto', N'Distrito 2, Av. Colombia N* 333')
GO

INSERT INTO [dbo].[tblCondominiosData] ([idCondominio], [name], [pin], [dbname], [address]) VALUES (N'5', N'Las Vegas', N'vegas', N'condominio_celeste', N'EE.UU. Av. Luna roja')
GO

SET IDENTITY_INSERT [dbo].[tblCondominiosData] OFF
GO


-- ----------------------------
-- Table structure for tblNombresServicios
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblNombresServicios]') AND type IN ('U'))
	DROP TABLE [dbo].[tblNombresServicios]
GO

CREATE TABLE [dbo].[tblNombresServicios] (
  [id_servicio] int  IDENTITY(1,1) NOT NULL,
  [servicio] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [creado_en] date DEFAULT getdate() NULL,
  [acronimo] varchar(20) COLLATE Modern_Spanish_CI_AS  NULL
)
GO

ALTER TABLE [dbo].[tblNombresServicios] SET (LOCK_ESCALATION = TABLE)
GO


-- ----------------------------
-- Records of tblNombresServicios
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblNombresServicios] ON
GO

INSERT INTO [dbo].[tblNombresServicios] ([id_servicio], [servicio], [creado_en], [acronimo]) VALUES (N'3', N'Electropaz de la paz', N'2024-05-17', N'ELAPAZ')
GO

SET IDENTITY_INSERT [dbo].[tblNombresServicios] OFF
GO


-- ----------------------------
-- Table structure for tblPublicidad
-- ----------------------------
IF EXISTS (SELECT * FROM sys.all_objects WHERE object_id = OBJECT_ID(N'[dbo].[tblPublicidad]') AND type IN ('U'))
	DROP TABLE [dbo].[tblPublicidad]
GO

CREATE TABLE [dbo].[tblPublicidad] (
  [name] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [image] bit  NULL,
  [on_server] bit  NULL,
  [url] varchar(500) COLLATE Modern_Spanish_CI_AS  NULL,
  [url_redirect] varchar(255) COLLATE Modern_Spanish_CI_AS  NULL,
  [id_publicidad] int  IDENTITY(1,1) NOT NULL,
  [status] bit  NULL
)
GO

ALTER TABLE [dbo].[tblPublicidad] SET (LOCK_ESCALATION = TABLE)
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: video, 1: image',
'SCHEMA', N'dbo',
'TABLE', N'tblPublicidad',
'COLUMN', N'image'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: servidor externo, 1: en servidor',
'SCHEMA', N'dbo',
'TABLE', N'tblPublicidad',
'COLUMN', N'on_server'
GO

EXEC sp_addextendedproperty
'MS_Description', N'Url para redirigir (url, whatsapp, youtube)',
'SCHEMA', N'dbo',
'TABLE', N'tblPublicidad',
'COLUMN', N'url_redirect'
GO

EXEC sp_addextendedproperty
'MS_Description', N'0: alta, 1: baja',
'SCHEMA', N'dbo',
'TABLE', N'tblPublicidad',
'COLUMN', N'status'
GO


-- ----------------------------
-- Records of tblPublicidad
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblPublicidad] ON
GO

SET IDENTITY_INSERT [dbo].[tblPublicidad] OFF
GO


-- ----------------------------
-- Auto increment value for tblCondominiosData
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblCondominiosData]', RESEED, 5)
GO


-- ----------------------------
-- Primary Key structure for table tblCondominiosData
-- ----------------------------
ALTER TABLE [dbo].[tblCondominiosData] ADD CONSTRAINT [PK__tblCondo__E7E8D6105EDB10CA] PRIMARY KEY CLUSTERED ([idCondominio])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblNombresServicios
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblNombresServicios]', RESEED, 3)
GO


-- ----------------------------
-- Primary Key structure for table tblNombresServicios
-- ----------------------------
ALTER TABLE [dbo].[tblNombresServicios] ADD CONSTRAINT [PK__tblNombr__6FD07FDC356043FC] PRIMARY KEY CLUSTERED ([id_servicio])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO


-- ----------------------------
-- Auto increment value for tblPublicidad
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblPublicidad]', RESEED, 1)
GO


-- ----------------------------
-- Primary Key structure for table tblPublicidad
-- ----------------------------
ALTER TABLE [dbo].[tblPublicidad] ADD CONSTRAINT [PK__tblAnunc__6FD07FDC5F65F773] PRIMARY KEY CLUSTERED ([id_publicidad])
WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)  
ON [PRIMARY]
GO

