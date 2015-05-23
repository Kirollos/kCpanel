-- kCpanel MySQL Structure

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Table structure for table `Accounts`
--

CREATE TABLE IF NOT EXISTS `Accounts` (
  `Username` varchar(100) NOT NULL,
  `Password` varchar(500) NOT NULL,
  `IsAdmin` int(1) NOT NULL,
  UNIQUE KEY `Username` (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Server_Infos`
--

CREATE TABLE IF NOT EXISTS `Server_Infos` (
  `Server_IP` varchar(20) NOT NULL,
  `Server_Port` int(11) NOT NULL,
  `Server_Name` varchar(500) NOT NULL,
  `Server_RCON` varchar(250) NOT NULL,
  `Allow_Access` varchar(500) NOT NULL,
  `SSH_Port` int(11) NOT NULL,
  `SSH_Username` varchar(100) NOT NULL,
  `SSH_Password` varchar(250) NOT NULL,
  `ServerDir` varchar(500) NOT NULL DEFAULT '~/samp03',
  `ServerExec` varchar(500) NOT NULL DEFAULT './samp03svr',
  `Stop_RCONCMD` varchar(50) NOT NULL DEFAULT 'exit',
  `Restart_RCONCMD` varchar(50) NOT NULL DEFAULT 'gmx'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
