<?

	require_once("DatabaseTool.class.php");

	$from = $_GET["from"];

	switch($from)
	{
		default:
			echo "{}";
			break;

		case "users":
			require_once("UsersManager.class.php");
			$mgr = new UsersManager();
			echo json_encode($mgr->getall());
			break;

		case "permissions":
			require_once("PermissionsManager.class.php");
			$mgr = new PermissionsManager();
			echo json_encode($mgr->getall());
			break;

		case "actions":
			require_once("ActionsManager.class.php");
			$mgr = new ActionsManager();
			echo json_encode($mgr->getall());
			break;

		case "organizations":
			require_once("OrganizationsManager.class.php");
			$mgr = new OrganizationsManager();
			echo json_encode($mgr->getall());
			break;

		case "suborganizations":
			require_once("SuborganizationsManager.class.php");
			$mgr = new SuborganizationsManager();
			echo json_encode($mgr->getall());
			break;

		case "scrapeurls":
			require_once("ScrapeurlsManager.class.php");
			$mgr = new ScrapeurlsManager();
			echo json_encode($mgr->getall());
			break;

		case "ignoreurls":
			require_once("IgnoreurlsManager.class.php");
			$mgr = new IgnoreurlsManager();
			echo json_encode($mgr->getall());
			break;

		case "documents":
			require_once("DocumentsManager.class.php");
			$mgr = new DocumentsManager();
			echo json_encode($mgr->getall());
			break;

		case "documenttexts":
			require_once("DocumenttextsManager.class.php");
			$mgr = new DocumenttextsManager();
			echo json_encode($mgr->getall());
			break;

		case "words":
			require_once("WordsManager.class.php");
			$mgr = new WordsManager();
			echo json_encode($mgr->getall());
			break;

		case "searches":
			require_once("SearchesManager.class.php");
			$mgr = new SearchesManager();
			echo json_encode($mgr->getall());
			break;

		case "runs":
			require_once("RunsManager.class.php");
			$mgr = new RunsManager();
			echo json_encode($mgr->getall());
			break;

	}

?>