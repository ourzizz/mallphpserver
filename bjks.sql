-- MySQL dump 10.13  Distrib 5.6.33, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: bjks2
-- ------------------------------------------------------
-- Server version	5.6.33-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `adcustomer`
--

DROP TABLE IF EXISTS `adcustomer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `adcustomer` (
  `customerid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `phoneNo` varchar(45) NOT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `adcustomer`
--

LOCK TABLES `adcustomer` WRITE;
/*!40000 ALTER TABLE `adcustomer` DISABLE KEYS */;
/*!40000 ALTER TABLE `adcustomer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `addetail`
--

DROP TABLE IF EXISTS `addetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `addetail` (
  `idaddetail` int(11) NOT NULL AUTO_INCREMENT,
  `customerid` int(11) NOT NULL,
  `adcontent` varchar(300) NOT NULL,
  `adstarttime` date NOT NULL,
  `adendtime` date NOT NULL,
  `adcost` int(11) DEFAULT NULL,
  PRIMARY KEY (`idaddetail`),
  KEY `fk_addetail_adcustomer1_idx` (`customerid`),
  CONSTRAINT `fk_addetail_adcustomer1` FOREIGN KEY (`customerid`) REFERENCES `adcustomer` (`customerid`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `addetail`
--

LOCK TABLES `addetail` WRITE;
/*!40000 ALTER TABLE `addetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `addetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cSessionInfo`
--

DROP TABLE IF EXISTS `cSessionInfo`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cSessionInfo` (
  `open_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uuid` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `skey` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_visit_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_info` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`open_id`),
  KEY `openid` (`open_id`) USING BTREE,
  KEY `skey` (`skey`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='会话管理用户信息';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cSessionInfo`
--

LOCK TABLES `cSessionInfo` WRITE;
/*!40000 ALTER TABLE `cSessionInfo` DISABLE KEYS */;
/*!40000 ALTER TABLE `cSessionInfo` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ksfile`
--

DROP TABLE IF EXISTS `ksfile`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ksfile` (
  `ksfileid` int(11) NOT NULL AUTO_INCREMENT,
  `ksid` varchar(20) NOT NULL,
  `wjmc` varchar(100) NOT NULL,
  `filepubtime` varchar(20) NOT NULL,
  `webaddress` varchar(200) NOT NULL,
  `article` mediumtext,
  PRIMARY KEY (`ksfileid`),
  KEY `fk_ksfile_kstype_idx` (`ksid`),
  CONSTRAINT `fk_ksfile_kstype` FOREIGN KEY (`ksid`) REFERENCES `kstype` (`ksid`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ksfile`
--

LOCK TABLES `ksfile` WRITE;
/*!40000 ALTER TABLE `ksfile` DISABLE KEYS */;
INSERT INTO `ksfile` VALUES (1,'gwyks','毕节市2017年统一面向社会公开招录公务员拟录用人员公示','2018-08-29','http://bj.gzrst.gov.cn/zwgk/xxgkml/gggs/201805/t20180528_3278494.html','<table id=\"c\" class=\"ke-zeroborder\" width=\"95%\" cellspacing=\"1\" cellpadding=\"0\" border=\"0\" align=\"center\"><tbody><tr><td style=\"font-size:16px;color:#F00;font-weight:bold;\" align=\"center\"><h1>毕节市2017年统一面向社会公开招录公务员拟录用人员公示</h1><br /><hr /></td></tr><tr><td><table style=\"margin:0 auto;\" class=\"ke-zeroborder\" width=\"90%\" border=\"0\" align=\"center\"><tbody><tr><td align=\"center\"><br /></td><td align=\"center\"><br /></td><td align=\"center\"><br /></td><td align=\"center\"><br /></td><td align=\"center\"><br /></td><td align=\"center\"><br /></td></tr></tbody></table></td></tr><tr><td class=\"bt_content\"><span id=\"Zoom\"> <p style=\"text-indent:32pt;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">根据《贵州省2017年省、市、县、乡四级机关统一面向社会公开招录公务员工作简章》的规定，经笔试、面试、体能测评、孕期结束后体检、考察等程序，赵维等7名同志确定为拟录用人员，现予以公示。请社会各界进行监督，有问题请从公示之日起7个工作日(2018年5月28日-6月5日)内向毕节市招考办反映或举报。</span></span></p><p><br /></p><p style=\"text-indent:40pt;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">联系电话：0857-8246095（毕节市纪委）</span></span></p><p style=\"text-indent:120pt;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">0857-8221482（中共毕节市委组织部）</span></span></p><p style=\"text-indent:120pt;margin-left:0.05pt;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">0857-8222297（毕节市人力资源和社会保障局）</span></span></p><p style=\"text-indent:32pt;\"><br /></p><p style=\"text-indent:32pt;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">附件：</span></span><span style=\"font-size:18px;\"><a href=\"http://bj.gzrst.gov.cn/zwgk/xxgkml/gggs/201805/W020180528396895607168.xls\">毕节市2017年统一面向社会公开招录选调生和公务员（人民警察）拟录用人员名单.xls</a></span></p><p style=\"text-align:right;\"><br /></p><p style=\"text-align:right;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">毕节市公务员招考办公室</span></span></p><p style=\"text-align:right;\"><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">201</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">8</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">年</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">5</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">月</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">28</span></span><span style=\"font-size:16pt;\"><span style=\"font-family:仿宋_gb2312;\">日</span></span></p></span></td></tr></tbody></table>'),(2,'gwyks','毕节市2018年考试录用公务员和人民警察笔试工作温馨提示---','2018-05-12','http://bj.gzrst.gov.cn/zwgk/xxgkml/gggs/201804/t20180412_3234954.html',NULL),(3,'jsj','毕节市2017年第二次计算机应用能力考试合格证发放的通知','2017-01-01','http://bj.gzrst.gov.cn/ywzl/rsks/jsjyynlks/201712/t20171204_3074469.html',NULL),(4,'szyf','准考证打印','2018年7月15日','http://www.gzpta.gov.cn/wsbmrk4.htm',NULL),(5,'gwyks','18公务员报考准考证打印','2018-01-24','http://www.gzpta.gov.cn/wsbmrk4.htm',NULL),(6,'kjs','毕节市人力资源和社会保障局 毕节市财政局关于公布毕节市2017年度全国会计专业技术中级资格考试合格人员名单的通知','2018-05-17','http://www.bijie.gov.cn/bm/bjsczj/dt/tzgg/243556.shtml',NULL),(7,'gwyks','毕节市2018年公开招录选调生、公务员（人民警察）、市直机关基层培养项目公务员面试、体能测评工作有关事宜的公告','2018-07-16','http://www.bijie.gov.cn/yw/tzgg/rszp/255649.shtml',NULL),(8,'zcchs','关于做好2018年度注册测绘师资格考试考务工作的通知','2018-7-5','http://www.gzpta.gov.cn/b.aspx?a=77880',NULL),(9,'zcsbjls','贵州省人力资源和社会保障厅贵州省质量技术监督局关于做好2018年度注册设备监理师执业资格考试工作的通知','2000-01-01','http://www.cpta.com.cn/n1/2018/0112/c373102-29761392.html',NULL),(10,'tgjs','毕节市2018特岗教师招聘第二阶段 体检须知','2018-05-06','http://bjjypt.bjjy.gov.cn/gsl/5339.jhtml',NULL),(11,'jjs','贵州省人力资源和社会保障厅关于做好2018年度经济专业技术资格','2018/7/18','http://www.gzpta.gov.cn/b.aspx?a=77896',NULL),(12,'zyys','省人力资源社会保障厅省食品药品监管局关于做好2018年度全国执业药师资格考试工作的通知','2018-07-18','http://www.gzpta.gov.cn/b.aspx?a=77894',NULL),(13,'rszp','毕节市工业能源投资建设有限公司2018年面向社会公开招聘工作人员面试成绩公示','2018-07-10','http://www.bijie.gov.cn/yw/tzgg/rszp/256157.shtml',NULL),(14,'jjs','毕节市人力资源和社会保障局关于做好2018年度经济专业技术资格考试工作的通知〔2018〕203号 ------','2018/7/23','毕节人力资源社会保障网->人事考试->资格考试',NULL),(15,'yjjzs','关于做好2018年度一级建造师资格考试报名工作的通知','2018/7/23','贵州人事考试信息网',NULL),(35,'fyzy','关于做好2018年度下半年翻译专业资格（水平）考试工作的通知黔人社通〔2018〕273号','2018-07-27','贵州人事考试网->考试通知',NULL),(36,'gwyks','毕节市2018年公开招录选调生、公务员（人民警察）、市直机关基层培养项目公务员面试成绩、体能测评成绩、总成绩和职位减少情况的公示','2018-07-30','http://www.bijie.gov.cn/yw/tzgg/rszp/258853.shtml',NULL),(37,'jsj','毕节市2018年度第一次专业技术人员计算机应用能力考试工作的通知','2018-08-01','毕节人力资源和社会保障局',NULL),(38,'gwyks','毕节市2018年公开招录选调生、公务员（人民警察）、市直机关基层培养项目公务员体检有关事宜的公告','2018-08-06','毕节人民政府网',NULL),(39,'zcaq','省人力资源社会保障厅省安全监管局关于做好2018年度注册安全工程师执业资格考试工作的通知','2018-08-13','贵州人事考试',NULL),(40,'zcxfgcs','贵州省人力资源和社会保障厅关于做好2018年度一级注册消防工程师资格考试考务工作的通知','2000-01-01','贵州人事考试网',NULL),(41,'zjgcs','省人力资源社会保障厅省住房城乡建设厅关于做好2018年度造价工程师职业资格考试考务工作的通知','2000-01-01','贵州人事考试网',NULL);
/*!40000 ALTER TABLE `ksfile` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ksmsg`
--

DROP TABLE IF EXISTS `ksmsg`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ksmsg` (
  `msgid` int(11) NOT NULL AUTO_INCREMENT,
  `msgcontent` varchar(500) NOT NULL,
  `msgpubtime` varchar(20) NOT NULL,
  `ksfileid` int(11) NOT NULL,
  `ksid` varchar(45) DEFAULT NULL,
  `deadtime` date NOT NULL,
  PRIMARY KEY (`msgid`),
  KEY `fk_ksmsg_ksfile1_idx` (`ksfileid`),
  CONSTRAINT `fk_ksmsg_ksfile1` FOREIGN KEY (`ksfileid`) REFERENCES `ksfile` (`ksfileid`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ksmsg`
--

LOCK TABLES `ksmsg` WRITE;
/*!40000 ALTER TABLE `ksmsg` DISABLE KEYS */;
INSERT INTO `ksmsg` VALUES (1,'根据《贵州省2017年省、市、县、乡四级机关统一面向社会公开招录公务员工作简章》的规定，经笔试、面试、体能测评、孕期结束后体检、考察等程序，赵维等7名同志确定为拟录用人员，现予以公示。','2018年7月8日',1,'gwyks','2018-07-24'),(2,'广大考生应该自觉树立诚信应考观念，严格按照《考场规则》要求，请自觉遵守《刑法修正案（九）》有关条款，依法参加考试，诚信应考。对考试期间发现的违纪作弊行为，将按《公务员考试录用违纪违规行为处理办法》等规定严肃处理。','2018年04月12日',2,'gwyks','2018-06-01'),(3,'1、请于4月16日9：00——4月19日18：00登录“贵州人事考试信息网”打印笔试准考证。打印后请认真阅读每项信息，特别是考点名称、地址、考场号、座位号信息，如有疑问请及时与考试部门联系，联系人：金开武，联系电话：8222949。','2018年04月12日',2,'gwyks','2018-06-01'),(4,'3、考试期间正值我市旅游旺季，过夜游客较多，住宿接待紧张，　建议提前作好考试期间食宿安排。','2018年04月12日',2,'gwyks','2018-06-01'),(5,'4、考生凭本人准考证和有效二代身份证参加考试，请妥善保管并到考场。身份证过期、遗失或没有办理二代身份证的考生，请及时到公安部门办理。','2018年04月12日',2,'gwyks','2018-06-01'),(6,'各位考生：    毕节市2017年第二次计算机应用能力考试合格证书已办理完毕。考生请携带本人身份证到毕节市人事考试中心（麻园大道中段社保大楼九楼）领取。','2017-10-13',3,'jsj','2018-10-24'),(7,'公务员面试准考证打印时间7-16-7月20','2017年7月15日',5,'gwyks','2018-07-20'),(8,'（一）面试时间：2018年7月21日。 面试人员面试当日上午7：20起凭《面试准考证》、第二代有效《居民身份证》原件进入候考室，8：10仍未到达指定候考室的考生视为自动弃权，责任自负。证件与本人不符或证件不全的，不得进入候考室。面试考生请自带饮用水。（二）面试地点及地址：贵州工程应用技术学院绣山教学楼A栋、绣山教学楼B栋、仲群楼、建工楼（毕节市七星关区学院路）。（三）打印面试准考证时间（网址：http://www.gzpta.gov.cn/）打印时间2018年7月17日09：00— 2018年7月21日08：10。','2018-7-16',7,'gwyks','2018-07-21'),(9,'注册测绘师资格考试,网上报名时间为2018年7月6日至7月26日,地址www.cpta.com.cn.网上报名资格审查咨询电话0851—86827321，技术咨询电话0851—86810676。','2018-07-13',8,'zcchs','2018-07-26'),(10,'18年注册设备监理师考试报名时间：2018年7月9日至7月26日。考试时间9月8日，9月9日,报名地址（www.cpta.com.cn）,资格审查联系电话：0851—86517462,网上报名技术咨询电话0851—86810676','2018-7-16',9,'zcsbjls','2018-07-26'),(11,'2018特岗教师招聘体检时间：2018年7月18日。 二、体检医院：七星关区人民医院新院区','2018/7/16',10,NULL,'2018-07-18'),(12,'18年经济师，报名时间为2018年7月24日至8月13日,地址www.cpta.com.cn','2018/7/18',11,NULL,'2018-08-14'),(13,'全国统一的经济考试时间为11月3日、4日，按专业分两天上下午4个批次实施。若遇特殊情况无法正常完成考试，统一于11月11日组织补考。','2018/7/18',11,'','2018-10-24'),(14,'2018年度执业药师资格考试定于10月13日至14日举行','2018-07-13',12,'zyys','2018-10-15'),(15,'执业药师资格考试报名时间为2018年7月27日至8月16日。报名地址中国人事考试网（www.cpta.com.cn）资格审查咨询电话：0851-86807315、86807441，网上报名技术咨询电话：0851-86810676。','2018-07-13',12,'','2018-08-17'),(16,'经济专业报名条件,初级,具备高中以上学历或初中毕业参加工作满六年。 中级 1．大专毕业工作满三年，本科工作满两年。其余报考条件参照原文件或可咨询考试中心8222949','2018/7/23',14,'','2018-08-14'),(17,'一建:1.报名时间2018年7月14日上午9：00—7月21日16:002.资格审查系统开通预约时间2018年7月22日上午9：00—7月27日16:003.现场资格审查时间2018年7月23日上午9：00—7月27日16:004.缴费时间2018年7月24日上午9：00—7月29日16:005.准考证打印时间2018年9月10日上午9：00—9月14日16:00','2018/7/23',15,NULL,'2018-09-11'),(18,'一建：技术支持电话：0531-66680723（报名系统）、85360209（资格审查系统）。报名政策咨询电话：0851-85360020、85360006、85360409、85360767、85360372、85360420、85360014、85360368、85360873、85360438、85360364。','2018/7/23',15,'','2018-10-24'),(19,'经济师考生:大家注意考试文件中规定条件为:从事专业工作满*年，即报名填表的专业年限，请按要求如实填写','2017-10-27',14,'','2018-08-14'),(20,'经济考试，报名表为考试通过后在资格审核环节才使用，请及时打印妥善保管','2018/7/24',14,NULL,'2018-08-14'),(26,'会计师请联系财政局','1',9,'kjs','2018-04-28'),(33,'翻译专业报名时间2018年9月6日至9月17日，地址（www.cpta.com.cn）网上报名技术咨询电话：0851-86810676，资格审查电话：0851-86830878','2018-07-28',35,'fyzy','2018-09-17'),(34,'翻译专业考试时间为18年11月18日，地点参照准考证','2018-07-28',35,'','2018-10-24'),(36,'2018年公务员、警察、选调生面试成绩已在毕节市人民政府网进行公式，监督电话：毕节市纪委（市监委）第四纪检监察组：0857-8339330。咨询电话：中共毕节市委组织部：0857-8221482毕节市人社局：0857-8222297\n','2018-07-30',36,'gwyks','2018-10-28'),(37,'毕节18年第一次计算机考试，报名时间为2018年8月3日-8月13日(报名地址http://jk.cpta.com.cn/)，考试时间8月21-8月31日，咨询电话8222949','2018-08-01',37,'jsj','2018-10-30'),(39,'公务员、选调生、警察准考证打印窗口已打开，开放时间：2018-07-17 09:00:00—2018-08-12 08:10:00 （贵州人事考试->准考证打印->2017年7月（毕节考区）公务员和人民警察面试－准考证打印入口）','2018-08-10',38,'','2018-08-13'),(40,'18经济师报名13日报名截止，缴费时间延长到2018年8月15日17','2017-10-27',1,'','2018-05-15'),(41,'经济师报名截止到13日，缴费时间2018年8月15日17：00前，请考生抓紧时间缴费','2018-08-13',14,'','2018-08-16'),(42,'注册安全工程师，报名时间为2018年8月14日至9月3日，地址www.cpta.com.cn。','2018-08-13',39,'','2018-09-04'),(43,'注册消防工程师考试时间：2018-11-10 11-11两天。报名时间2018年8月30日至9月18日，网上资格审查咨询电话：0851-85767319、85709540，报名技术咨询电话：0851-86810676。','2018-08-30',40,'','2018-11-18'),(44,'造价工程师考试时间：2018年10-27和10月28。报名时间 2018年8月27日－9月4日。 现场资格审查时间 2018年8月28日-9月4日（法定工作日）\n资格审查咨询电话：0851-85360205、85360295，网上报名技术咨询电话：0851-86810676。','2018-08-30',41,'','2018-10-28');
/*!40000 ALTER TABLE `ksmsg` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kstype`
--

DROP TABLE IF EXISTS `kstype`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kstype` (
  `ksid` varchar(20) NOT NULL,
  `ksname` varchar(100) NOT NULL,
  `hot` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`ksid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kstype`
--

LOCK TABLES `kstype` WRITE;
/*!40000 ALTER TABLE `kstype` DISABLE KEYS */;
INSERT INTO `kstype` VALUES ('fyzy','翻译专业','1','zyjs'),('gwyks','公务员考试','6','gwy'),('jjs','经济专业技术资格考试','1','zyjs'),('jsj','计算机应用能力考试','','zyjs'),('kjs','会计师考试',NULL,'zyjs'),('rszp','人事招聘考试',NULL,'qt'),('sydwlk','事业单位联考','','sydw'),('szyf','三支一扶','','qt'),('tgjs','特岗教师',NULL,'qt'),('yjjzs','一级建造师','','zyjs'),('zcaq','注册安全工程师','','zyjs'),('zcchs','注册测绘师','','zyjs'),('zcsbjls','注册设备监理师',NULL,'zyjs'),('zcwy','职称外语考试','3','zyjs'),('zcxfgcs','注册消防工程师','','zyjs'),('zjgcs','造价工程师','','zyjs'),('zyys','职业药师考试','','zyjs');
/*!40000 ALTER TABLE `kstype` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-01 16:15:50
