-- ----------------------------
-- Records of tblSubscriptionType
-- ----------------------------
SET IDENTITY_INSERT [dbo].[tblSubscriptionType] ON
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price], [status], [max_users]) VALUES (N'1', N'Gratuito', N'Comienzo fácil', N'1', N'0', N'0', N'Esta suscripción te permite disfrutar de la aplicación por un mes de forma gratuita.', N'6', N'0', N'["Casilleros para recibir pedidos","Casilla con Número Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificación y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de número de teléfono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad."]', N'0', N'0', N'1', N'5')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price], [status], [max_users]) VALUES (N'2', N'Estandard', N'Experiencia optima', N'1', N'0', N'400', N'Permite recibir avisos sobre correspondencia en los casilleros.', N'1', N'0', N'["Casilleros para recibir pedidos","Casilla con N\u00famero Postal Certificada para recibir correspondencia nacional e internacional (Amazon, Ebay, Alibaba, otros)","Notificaci\u00f3n y Reporte de pedidos o correspondencia online.","Protege a mantener tu privacidad de n\u00famero de tel\u00e9fono.","Protege tus pedidos o correspondencia con protocolos de bioseguridad.","Asistencia y soporte"]', N'1', N'500', N'1', N'6')
GO

INSERT INTO [dbo].[tblSubscriptionType] ([id_subscription_type], [name], [tag], [see_lockers], [see_services], [price], [description], [months_duration], [courrier], [details], [iva], [annual_price], [status], [max_users]) VALUES (N'3', N'Premium', N'Solución personalizada', N'1', N'1', N'750', N'Permite recibir avisos de correspondencia y tambien la posibilidad de pagar tus servicios.', N'1', N'0', N'["Beneficios de suscripción Standard","Pago de Servicios Básicos.","Notificación y reporte de Pago de Servicios online.","Sistema de pago mediante QR.","Recepción del baucher de Pago de Servicios vía correo electrónico."]', N'1', N'900', N'1', N'4')
GO


SET IDENTITY_INSERT [dbo].[tblSubscriptionType] OFF
GO
-- ----------------------------
-- Auto increment value for tblSubscriptionType
-- ----------------------------
DBCC CHECKIDENT ('[dbo].[tblSubscriptionType]', RESEED, 3)
GO
