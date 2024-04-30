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

 Date: 30/04/2024 19:09:38
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

