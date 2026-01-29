<?php
namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\LogModel;

class LogController extends Controller
{
    private LogModel $logModel;

    public function __construct()
    {
        // Accès réservé aux admins connectés
        if (empty($_SESSION["admin_logged"])) {
            header("Location: /admin/login");
            exit;
        }

        $this->logModel = new LogModel();
    }

    // Liste des logs
    public function index()
    {
        // Nombre de logs par page
        $perPage = 5;
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

        // Compter les logs
        $total = $this->logModel->countAll();
        $offset = ($page - 1) * $perPage;

        // Pagination
        $logs = $this->logModel->getPage($perPage, $offset);

        // Envoi a la vue
        $this->render('admin/logs/index', [
            'logs' => $logs,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total
        ], "layout_admin");
    }
}