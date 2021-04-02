SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";




INSERT INTO `roadgroup` (`roadgroupid`, `year`, `rggroupid`, `rgsubgroupid`, `name`, `households`, `electors`, `distance`, `priority`, `prioritygroup`, `kml`, `minlat`, `maxlat`, `minlong`, `maxlong`, `midlat`, `midlong`, `note`) VALUES
('BLY_C1', 2021, 'RG_BLY', 'RG_BLY_C', 'Abbotsford Rd', 219, 365, 1.98929, 'M', '80', ' BLY_C1.kml', ' -1.8110899925231934', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_C2', 2021, 'RG_BLY', 'RG_BLY_C', 'Baskeyfield Cl', 130, 249, 1.56091, 'H', '56', ' BLY_C2.kml', ' -1.81351900100708', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_C3', 2021, 'RG_BLY', 'RG_BLY_C', 'Haymoor', 199, 374, 2.46375, 'M', '106', ' BLY_C3.kml', ' -1.8124099969863892', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E1', 2021, 'RG_BLY', 'RG_BLY_E', 'Birchwood Rd', 153, 314, 1.50943, 'L', '162', ' BLY_E1.kml', ' -1.8029199838638306', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E2', 2021, 'RG_BLY', 'RG_BLY_E', 'Curlew Cl', 117, 224, 1.89626, 'M', '119', ' BLY_E2.kml', ' -1.8047000169754028', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E3', 2021, 'RG_BLY', 'RG_BLY_E', 'Gable Croft', 164, 337, 2.48349, 'L', '182', ' BLY_E3.kml', ' -1.8068000078201294', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E4', 2021, 'RG_BLY', 'RG_BLY_E', 'Hartslade', 136, 278, 1.59954, 'L', '158', ' BLY_E4.kml', ' -1.8075799942016602', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_E5', 2021, 'RG_BLY', 'RG_BLY_E', 'Bracken Cl', 88, 174, 0.976436, 'L', '179', ' BLY_E5.kml', ' -1.8084800243377686', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_N1', 2021, 'RG_BLY', 'RG_BLY_N', 'Cornfield', 223, 339, 2.17104, 'H', '18', ' BLY_N1.kml', ' -1.8085600137710571', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_N2', 2021, 'RG_BLY', 'RG_BLY_N', 'Yew Tree', 149, 270, 1.16977, 'M', '122', ' BLY_N2.kml', ' -1.8074549436569214', NULL, NULL, NULL, NULL, NULL, NULL),
('BLY_W1', 2021, 'RG_BLY', 'RG_BLY_W', 'Broadlands', 237, 484, 2.9859, 'L', '185', ' BLY_W1.kml', ' -1.818019986152649', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C1', 2021, 'RG_CHD', 'RG_CHD_C', 'Oakenfield', 209, 379, 1.89107, 'L', '181', ' CHD_C1.kml', ' -1.8401000499725342', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C2', 2021, 'RG_CHD', 'RG_CHD_C', 'Swallow Croft', 166, 273, 1.67742, 'M', '102', ' CHD_C2.kml', ' -1.8401600122451782', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C3', 2021, 'RG_CHD', 'RG_CHD_C', 'Leasowe', 124, 225, 1.72954, 'L', '176', ' CHD_C3.kml', ' -1.8364100456237793', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C4', 2021, 'RG_CHD', 'RG_CHD_C', 'Dimbles Lane', 131, 213, 0.763773, 'L', '163', ' CHD_C4.kml', ' -1.8327300548553467', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_C5', 2021, 'RG_CHD', 'RG_CHD_C', 'Weston Road', NULL, NULL, 1.67553, NULL, NULL, ' CHD_C5.kml', ' -1.839419960975647', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N1', 2021, 'RG_CHD', 'RG_CHD_N', 'Garrick Road', 154, 328, 1.52114, 'H', '28.5', ' CHD_N1.kml', ' -1.848080039024353', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N2', 2021, 'RG_CHD', 'RG_CHD_N', 'Grange Lane', 162, 295, 1.62808, 'H', '35', ' CHD_N2.kml', ' -1.842229962348938', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N3', 2021, 'RG_CHD', 'RG_CHD_N', 'Pauls Walk', 269, 472, 2.49059, 'L', '175', ' CHD_N3.kml', ' -1.8395899534225464', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_N4', 2021, 'RG_CHD', 'RG_CHD_N', 'North', NULL, NULL, 1.42722, NULL, NULL, ' CHD_N4.kml', ' -1.8393399715423584', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S1', 2021, 'RG_CHD', 'RG_CHD_S', 'Charters', 94, 193, 1.86154, 'L', '184', ' CHD_S1.kml', ' -1.831470012664795', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S2', 2021, 'RG_CHD', 'RG_CHD_S', 'Gaia Lane', 107, 206, 2.95357, 'M', '136', ' CHD_S2.kml', ' -1.833490014076233', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S3', 2021, 'RG_CHD', 'RG_CHD_S', 'Anson Ave', 119, 202, 1.45883, 'M', '133', ' CHD_S3.kml', ' -1.8353899717330933', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_S4', 2021, 'RG_CHD', 'RG_CHD_S', 'Beacon St', 116, 203, 1.54311, 'M', '111', ' CHD_S4.kml', ' -1.839050054550171', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_X1', 2021, 'RG_CHD', 'RG_CHD_X', 'Outside City', NULL, NULL, 0.448949, NULL, NULL, ' CHD_X1.kml', ' -1.85050630569458', NULL, NULL, NULL, NULL, NULL, NULL),
('CHD_XW', 2021, 'RG_CHD', 'RG_CHD_N', 'Not Walks', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C1', 2021, 'RG_CUR', 'RG_CUR_C', 'Curborough', 171, 347, 1.70145, 'M', '88', ' CUR_C1.kml', ' -1.8293299674987793', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C2', 2021, 'RG_CUR', 'RG_CUR_C', 'Meadowbrook', 194, 353, 1.86864, 'H', '67', ' CUR_C2.kml', ' -1.8281400203704834', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_C3', 2021, 'RG_CUR', 'RG_CUR_C', 'Fallowfield', 118, 176, 1.00758, 'L', '160', ' CUR_C3.kml', ' -1.8251440525054932', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E1', 2021, 'RG_CUR', 'RG_CUR_E', 'Giles', 132, 237, 1.39898, 'M', '116', ' CUR_E1.kml', ' -1.8341460227966309', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E2', 2021, 'RG_CUR', 'RG_CUR_E', 'Field', 170, 330, 1.42309, 'H', '66', ' CUR_E2.kml', ' -1.8310099840164185', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E3', 2021, 'RG_CUR', 'RG_CUR_E', 'David Garrick Gardens', 153, 226, 1.22595, 'M', '134', ' CUR_E3.kml', ' -1.8330899477005005', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_E4', 2021, 'RG_CUR', 'RG_CUR_E', 'Dimbles Lane', 158, 281, 1.34243, 'M', '128.5', ' CUR_E4.kml', ' -1.8317699432373047', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_N1', 2021, 'RG_CUR', 'RG_CUR_N', 'Norwich', 149, 306, 1.51263, 'M', '95', ' CUR_N1.kml', ' -1.8258670568466187', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_N2', 2021, 'RG_CUR', 'RG_CUR_N', 'Lincoln', 181, 322, 0.919085, 'H', '21', ' CUR_N2.kml', ' -1.8221800327301025', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_N3', 2021, 'RG_CUR', 'RG_CUR_N', 'Saxon Gate', 181, 322, 0.802763, '?', '?', ' CUR_N3.kml', ' -1.8230520486831665', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S1', 2021, 'RG_CUR', 'RG_CUR_S', 'Leyfields', 112, 205, 1.50407, 'M', '141', ' CUR_S1.kml', ' -1.8301600217819214', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S2', 2021, 'RG_CUR', 'RG_CUR_S', 'Ponesfield', 174, 363, 1.6023, 'L', '183', ' CUR_S2.kml', ' -1.8270219564437866', NULL, NULL, NULL, NULL, NULL, NULL),
('CUR_S3', 2021, 'RG_CUR', 'RG_CUR_S', 'Purcell Ave', 159, 267, 1.79795, 'L', '147', ' CUR_S3.kml', ' -1.8274630308151245', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_A1', 2021, 'RG_LEO', 'RG_LEO_A', 'Hallam Park', 50, NULL, 0.819037, NULL, NULL, ' LEO_A1.kml', ' -1.8511170148849487', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C1', 2021, 'RG_LEO', 'RG_LEO_C', 'Walsall Rd', 226, 444, 2.28598, 'M', '98', ' LEO_C1.kml', ' -1.8515100479125977', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C2', 2021, 'RG_LEO', 'RG_LEO_C', 'Christchurch Ln', 249, 459, 2.93678, 'M', '76', ' LEO_C2.kml', ' -1.847409963607788', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C3', 2021, 'RG_LEO', 'RG_LEO_C', 'Walnut Walk', 181, 368, 2.44791, 'M', '101', ' LEO_C3.kml', ' -1.8487999439239502', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C4', 2021, 'RG_LEO', 'RG_LEO_C', 'Whithouse Dr', 163, 296, 2.03495, 'H', '9', ' LEO_C4.kml', ' -1.844851016998291', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C5', 2021, 'RG_LEO', 'RG_LEO_C', 'Blakeman Way', 132, 264, 1.99741, 'H', '20', ' LEO_C5.kml', ' -1.8420350551605225', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C6', 2021, 'RG_LEO', 'RG_LEO_C', 'St Foy Ave', 126, 204, 1.10716, 'H', '1', ' LEO_C6.kml', ' -1.8366459608078003', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C7', 2021, 'RG_LEO', 'RG_LEO_C', 'Deykin Rd (part) ', 176, 311, 0.774513, 'H', '3', ' LEO_C7.kml', ' -1.8372299671173096', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C8', 2021, 'RG_LEO', 'RG_LEO_C', 'Sandfield Meadow', 188, 342, 1.26959, 'H', '8', ' LEO_C8.kml', ' -1.8382600545883179', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_C9', 2021, 'RG_LEO', 'RG_LEO_C', 'Lawrence Way', 103, 231, 1.35568, 'M', '91', ' LEO_C9.kml', ' -1.841189980506897', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N1', 2021, 'RG_LEO', 'RG_LEO_N', 'Beacon St', 168, 287, 0.869655, 'M', '82', ' LEO_N1.kml', ' -1.847301959991455', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N2', 2021, 'RG_LEO', 'RG_LEO_N', 'Harrington Walk', 212, 393, 2.15753, 'M', '93', ' LEO_N2.kml', ' -1.8467400074005127', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N3', 2021, 'RG_LEO', 'RG_LEO_N', 'Parkside Ct', 106, 159, 1.70916, 'M', '84', ' LEO_N3.kml', ' -1.8385900259017944', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N4', 2021, 'RG_LEO', 'RG_LEO_N', 'Mary Slater', 90, 172, 2.17275, 'H', '69', ' LEO_N4.kml', ' -1.837820053100586', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_N5', 2021, 'RG_LEO', 'RG_LEO_N', 'Ferndale Rd', 201, 380, 2.55836, 'M', '110', ' LEO_N5.kml', ' -1.8451299667358398', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W1', 2021, 'RG_LEO', 'RG_LEO_W', 'Bham Rd (part)', 197, 315, 1.15599, 'M', '74', ' LEO_W1.kml', ' -1.8333560228347778', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W2', 2021, 'RG_LEO', 'RG_LEO_W', 'Swan Rd', 186, 261, 1.65735, 'H', '6', ' LEO_W2.kml', ' -1.8328419923782349', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_W3', 2021, 'RG_LEO', 'RG_LEO_W', 'Lower Sandford St', 237, 310, 0.931123, 'M', '105', ' LEO_W3.kml', ' -1.8368459939956665', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_X1', 2021, 'RG_LEO', 'RG_LEO_X', 'Walsall Rd extension', NULL, NULL, 0.14657, NULL, NULL, ' LEO_X1.kml', ' -1.854701042175293', NULL, NULL, NULL, NULL, NULL, NULL),
('LEO_X2', 2021, 'RG_LEO', 'RG_LEO_X', 'Vacant Roads', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C1', 2021, 'RG_STJ', 'RG_STJ_C', 'Scott Close', 155, 274, 1.68802, 'M', '124', ' STJ_C1.kml', ' -1.8291399478912354', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C2', 2021, 'RG_STJ', 'RG_STJ_C', 'Chapel Lane', 126, 226, 1.17642, 'M', '114', ' STJ_C2.kml', ' -1.8280999660491943', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C3', 2021, 'RG_STJ', 'RG_STJ_C', 'Dovehouse Fields', 102, 196, 0.887864, 'M', '100', ' STJ_C3.kml', ' -1.8297699689865112', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C4', 2021, 'RG_STJ', 'RG_STJ_C', 'Trafalgar Way', 163, 291, 1.26617, 'H', '4', ' STJ_C4.kml', ' -1.830180048942566', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C5', 2021, 'RG_STJ', 'RG_STJ_C', 'Shortbutts Lane', 183, 352, 2.32248, 'M', '85', ' STJ_C5.kml', ' -1.8321199417114258', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_C6', 2021, 'RG_STJ', 'RG_STJ_C', 'Bham Rd', 121, 213, 1.19199, 'H', '7', ' STJ_C6.kml', ' -1.8322499990463257', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E1', 2021, 'RG_STJ', 'RG_STJ_E', 'Upper St John St', 133, 216, 0.76755, 'H', '40', ' STJ_E1.kml', ' -1.8262499570846558', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E2', 2021, 'RG_STJ', 'RG_STJ_E', 'Cherry Orchard', 210, 395, 2.65132, 'H', '5', ' STJ_E2.kml', ' -1.8259049654006958', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E3', 2021, 'RG_STJ', 'RG_STJ_E', 'Beech Gds', 202, 388, 2.87452, 'M', '109', ' STJ_E3.kml', ' -1.8236700296401978', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E4', 2021, 'RG_STJ', 'RG_STJ_E', 'Tamworth Road', 105, 183, 2.46706, 'M', '77', ' STJ_E4.kml', ' -1.82191002368927', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E5', 2021, 'RG_STJ', 'RG_STJ_E', 'Borrowcop', 161, 349, 3.14249, 'L', '167', ' STJ_E5.kml', ' -1.8227709531784058', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E6', 2021, 'RG_STJ', 'RG_STJ_E', 'Wentworth Dr', 144, 302, 2.36475, 'L', '170', ' STJ_E6.kml', ' -1.8137099742889404', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E7', 2021, 'RG_STJ', 'RG_STJ_E', 'Manor Rise', 107, 228, 2.78609, 'L', '146', ' STJ_E7.kml', ' -1.8202300071716309', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_E8', 2021, 'RG_STJ', 'RG_STJ_E', 'Longbridge', 148, 305, 2.47365, 'L', '166', ' STJ_E8.kml', ' -1.8254330158233643', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_W1', 2021, 'RG_STJ', 'RG_STJ_W', 'Chesterfield Rd', 164, 323, 2.02615, 'H', '68', ' STJ_W1.kml', ' -1.8366199731826782', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_W2', 2021, 'RG_STJ', 'RG_STJ_W', 'Agincourt', 207, 389, 3.07397, 'H', '11', ' STJ_W2.kml', ' -1.8404330015182495', NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_X1', 2021, 'RG_STJ', 'RG_STJ_X', 'No Walk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STJ_XW', 2021, 'RG_STJ', 'RG_STJ_X', 'Dont Walk', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C1', 2021, 'RG_STW', 'RG_STW_C', 'Stowe St', 140, 209, 1.11367, 'M', '132', ' STW_C1.kml', ' -1.8243000507354736', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C2', 2021, 'RG_STW', 'RG_STW_C', 'St Michael Road', 149, 263, 2.31241, 'M', '94', ' STW_C2.kml', ' -1.8219900131225586', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_C3', 2021, 'RG_STW', 'RG_STW_C', 'Wissage Rd', 100, 292, 1.19256, 'H', '64.5', ' STW_C3.kml', ' -1.8206499814987183', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E1', 2021, 'RG_STW', 'RG_STW_S', 'Lower Trent Valley Road', 189, 352, 0.41837, 'H', '30.5', ' STW_E1.kml', ' -1.8079440593719482', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E2', 2021, 'RG_STW', 'RG_STW_E', 'Rocklands Cres', 140, 253, 1.39365, 'M', '127', ' STW_E2.kml', ' -1.8160400390625', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E3', 2021, 'RG_STW', 'RG_STW_E', 'Valley Lane', 145, 257, 1.68312, 'M', '103', ' STW_E3.kml', ' -1.8140699863433838', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E4', 2021, 'RG_STW', 'RG_STW_E', 'Hobs Road', 154, 229, 0.834175, 'H', '52', ' STW_E4.kml', ' -1.8102999925613403', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E5', 2021, 'RG_STW', 'RG_STW_E', 'Scotch Orchard', 138, 271, 1.339, 'M', '104', ' STW_E5.kml', ' -1.8158899545669556', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_E6', 2021, 'RG_STW', 'RG_STW_E', 'Eastern Ave', 110, 211, 1.37236, 'H', '59', ' STW_E6.kml', ' -1.8143999576568604', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N1', 2021, 'RG_STW', 'RG_STW_N', 'Gilbert Road', 205, 352, 2.68945, 'L', '145', ' STW_N1.kml', ' -1.8215299844741821', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N2', 2021, 'RG_STW', 'RG_STW_N', 'Chadswell Heights', 141, 261, 1.57268, 'H', '43', ' STW_N2.kml', ' -1.8176089525222778', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N3', 2021, 'RG_STW', 'RG_STW_N', 'York Close', 132, 254, 1.96892, 'M', '75', ' STW_N3.kml', ' -1.8188899755477905', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_N4', 2021, 'RG_STW', 'RG_STW_N', 'Fecknam Way', 168, 313, 2.36078, 'L', '165', ' STW_N4.kml', ' -1.8199000358581543', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S1', 2021, 'RG_STW', 'RG_STW_S', 'Maxwell Close', 139, 230, 1.6814, 'H', '49.5', ' STW_S1.kml', ' -1.82383394241333', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S2', 2021, 'RG_STW', 'RG_STW_S', 'Maryvale Court', 121, 205, 1.9395, 'L', '155', ' STW_S2.kml', ' -1.815690040588379', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S3', 2021, 'RG_STW', 'RG_STW_S', 'BORW', 188, 381, 2.87484, 'M', '79', ' STW_S3.kml', ' -1.8172099590301514', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_S4', 2021, 'RG_STW', 'RG_STW_S', 'Deans Croft', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W1', 2021, 'RG_STW', 'RG_STW_W', 'Close', 114, 177, 1.63344, 'H', '32', ' STW_W1.kml', ' -1.8343000411987305', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W2', 2021, 'RG_STW', 'RG_STW_W', 'Bird St', 244, 338, 2.36671, 'H', '10', ' STW_W2.kml', ' -1.8309259414672852', NULL, NULL, NULL, NULL, NULL, NULL),
('STW_W3', 2021, 'RG_STW', 'RG_STW_W', 'Wade Street', 130, 166, 0.373298, 'H', '12', ' STW_W3.kml', ' -1.828529953956604', NULL, NULL, NULL, NULL, NULL, NULL);
COMMIT;