

DROP TABLE IF EXISTS `okooo_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `okooo_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hideone` varchar(50) NOT NULL DEFAULT ' ' COMMENT '比赛编号',
  `liansai` varchar(50) NOT NULL DEFAULT ' ' COMMENT '联赛名称',
  `match_time` varchar(255) NOT NULL DEFAULT ' ' COMMENT '比赛时间',
  `ifover` varchar(50) NOT NULL DEFAULT ' ' COMMENT '是否完场',
  `homename` varchar(255) NOT NULL DEFAULT ' ' COMMENT '主队名称',
  `homescore` varchar(50) NOT NULL DEFAULT ' ' COMMENT '主队得分',
  `awayscore` varchar(50) NOT NULL DEFAULT ' ' COMMENT '客队得分',
  `awayname` varchar(255) NOT NULL DEFAULT ' ' COMMENT '客队名称',
  `half` varchar(50) NOT NULL DEFAULT ' ' COMMENT '半场比分',
  `result` varchar(50) NOT NULL DEFAULT ' ' COMMENT '赛果310',
  `okooo_date` varchar(255) NOT NULL DEFAULT ' ' COMMENT '澳客投注日',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=370 DEFAULT CHARSET=utf8;


CREATE TABLE `recommend_data` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`wx_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '公众号id',
`wx_name` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '公众号名称',
`recommender_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT 'recomender.recommender_id',
`okooo_date` DATE NOT NULL DEFAULT '0000-00-00' COMMENT '澳客投注日期',
`match_no_before` VARCHAR(50) NOT NULL DEFAULT ' ' COMMENT '比赛编号',
`match_name_before` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '推荐比赛双方名称',
`match_result_before` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '推荐的结果',
`match_result_c` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '推荐结果的计算描述',
`match_name_okooo` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '澳客比赛双方名称',
`home_score_okooo` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '主队进球',
`away_score_okooo` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '客队进球',
`match_result_okooo` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '开奖结果',
`recommend_result` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '红或黑',
`comment` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '备注',
`checked` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '是否处理过，结果对比过',
`create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
`update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `source` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
`update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
`source_tag` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '来源标记,注册链接放置的source参数',
`source_name` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '来源',
`belong_mag_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '管理该来源的manager的mag_id',
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `manager` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
`update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
`mag_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT 'rand(1000,9999).time()',
`username` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT 'username',
`password` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT 'pwd',
`last_login` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '最后一次登录时间',
`login_times` VARCHAR(255) NOT NULL DEFAULT '0' COMMENT '登录次数',
`source_tag` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '来源标记',
`have_source` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '是否再source表中有数据',
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `recommender` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`create_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '创建时间',
`update_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '更新时间',
`wx_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '公众号id',
`wx_name` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '公众号名称,或推荐人名称',
`recommender_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '推荐人id,mag_id加_加wx_name得来',
`mag_id` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '管理员',
`comment` VARCHAR(255) NOT NULL DEFAULT ' ' COMMENT '备注',
PRIMARY KEY (`id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO recommender SET wx_id="zcssy0717",wx_name="足彩十三姨",create_date=now(),manager="liming",comment="";


INSERT INTO recommender SET wx_id="ygjp188",wx_name="一哥解盘",create_date=now(),manager="liming",comment="";

INSERT INTO recommender SET wx_id="huashanlunqiu2017",wx_name="华山论球",create_date=now(),manager="liming",comment="";

INSERT INTO recommender SET wx_id="lxjdd",wx_name="六小姐点灯",create_date=now(),manager="liming",comment="";

INSERT INTO recommender SET wx_id="wendanyx",wx_name="稳胆英雄",create_date=now(),manager="liming",comment="";

INSERT INTO recommender SET wx_id="shenni918",wx_name="神倪足球推荐",create_date=now(),manager="liming",comment="";