-- phpMyAdmin SQL Dump
-- version 4.4.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2015-09-14 19:57:28
-- 服务器版本： 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ITSERVICE_DB`
--

-- --------------------------------------------------------

--
-- 表的结构 `cp`
--
-- 会议表
CREATE TABLE IF NOT EXISTS `cp` (
  `id` int(11) NOT NULL,
  `applynum` varchar(100) DEFAULT NULL,
  `convtime` datetime DEFAULT NULL,
  `isd` varchar(100) DEFAULT NULL,
  `ispc` varchar(100) DEFAULT NULL,
  `convtype` varchar(200) DEFAULT NULL,
  `croom` varchar(200) DEFAULT NULL,
  `participant` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `dept`
--
-- 部门表
CREATE TABLE IF NOT EXISTS `dept` (
  `deptno` int(11) NOT NULL,
  `dname` varchar(200) DEFAULT NULL,
  `loc` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `hwp`
--
-- 硬件问题表
CREATE TABLE IF NOT EXISTS `hwp` (
  `id` int(11) NOT NULL,
  `applynum` varchar(100) DEFAULT NULL,
  `hwtype` varchar(200) DEFAULT NULL,
  `brand` varchar(200) DEFAULT NULL,
  `sn` varchar(50) DEFAULT NULL,
  `tinfo` varchar(200) DEFAULT NULL,
  `treason` varchar(200) DEFAULT NULL,
  `method` varchar(200) DEFAULT NULL,
  `sfurl` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `mission`
--
-- 任务表
CREATE TABLE IF NOT EXISTS `mission` (
  `id` int(11) NOT NULL,
  `applynum` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `dname` varchar(200) DEFAULT NULL,
  `subdname` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `tp` varchar(50) DEFAULT NULL,
  `addr` varchar(200) DEFAULT NULL,
  `creator` varchar(100) DEFAULT NULL,
  `distribute` varchar(200) DEFAULT NULL,
  `ptype` varchar(100) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `step` varchar(100) DEFAULT NULL,
  `ctime` datetime DEFAULT NULL,
  `optime` date DEFAULT NULL,
  `atime` datetime DEFAULT NULL,
  `stime` datetime DEFAULT NULL,
  `solver` varchar(100) DEFAULT NULL,
  `sfurl` varchar(200) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=99 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `pp`
--
-- 资产分配表
CREATE TABLE IF NOT EXISTS `pp` (
  `id` int(11) NOT NULL,
  `applynum` varchar(100) DEFAULT NULL,
  `distreason` varchar(200) DEFAULT NULL,
  `protype` varchar(300) DEFAULT NULL,
  `brand` varchar(200) DEFAULT NULL,
  `sn` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `ptype`
--
-- 问题类型表
CREATE TABLE IF NOT EXISTS `ptype` (
  `id` int(11) NOT NULL,
  `name` varchar(20) DEFAULT NULL,
  `url` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `sfp`
--
-- 软件类型表
CREATE TABLE IF NOT EXISTS `sfp` (
  `id` int(11) NOT NULL,
  `applynum` varchar(200) DEFAULT NULL,
  `sftype` varchar(200) DEFAULT NULL,
  `brand` varchar(200) DEFAULT NULL,
  `sn` varchar(100) DEFAULT NULL,
  `tinfo` varchar(300) DEFAULT NULL,
  `treason` varchar(300) DEFAULT NULL,
  `method` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `subdept`
--
-- 部门内部处室表
CREATE TABLE IF NOT EXISTS `subdept` (
  `subno` int(11) NOT NULL,
  `subdname` varchar(200) DEFAULT NULL,
  `deptno` int(11) DEFAULT NULL,
  `dname` varchar(300) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- 表的结构 `user`
--
-- 用户表  双MD5加密,请自行添加用户,role = it 则是维护人员,其它为管理人员
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL,
  `user` varchar(100) DEFAULT NULL,
  `password` varchar(200) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `telephone` varchar(100) DEFAULT NULL,
  `company` varchar(200) DEFAULT NULL,
  `auth` varchar(50) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cp`
--
ALTER TABLE `cp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `dept`
--
ALTER TABLE `dept`
  ADD PRIMARY KEY (`deptno`);

--
-- Indexes for table `hwp`
--
ALTER TABLE `hwp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mission`
--
ALTER TABLE `mission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pp`
--
ALTER TABLE `pp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ptype`
--
ALTER TABLE `ptype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sfp`
--
ALTER TABLE `sfp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subdept`
--
ALTER TABLE `subdept`
  ADD PRIMARY KEY (`subno`),
  ADD KEY `fk_deptno` (`deptno`);

--
-- Indexes for table `subdept1`
--
ALTER TABLE `subdept1`
  ADD PRIMARY KEY (`subno`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cp`
--
ALTER TABLE `cp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `dept`
--
ALTER TABLE `dept`
  MODIFY `deptno` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `hwp`
--
ALTER TABLE `hwp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `mission`
--
ALTER TABLE `mission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `pp`
--
ALTER TABLE `pp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `ptype`
--
ALTER TABLE `ptype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sfp`
--
ALTER TABLE `sfp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `subdept`
--
ALTER TABLE `subdept`
  MODIFY `subno` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `subdept1`
--
ALTER TABLE `subdept1`
  MODIFY `subno` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=95;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- 限制导出的表
--

--
-- 限制表 `subdept`
--
ALTER TABLE `subdept`
  ADD CONSTRAINT `fk_deptno` FOREIGN KEY (`deptno`) REFERENCES `dept` (`deptno`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
