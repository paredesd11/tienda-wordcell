<?php
class ReferenciaController extends Controller {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function index($id) {
        if (!$id) {
            $this->redirect('home');
        }

        try {
            $stmt = $this->db->prepare("SELECT * FROM referencias WHERE id = ?");
            $stmt->execute([$id]);
            $referencia = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$referencia) {
                $this->redirect('home');
            }

            $this->view('referencia/index', [
                'ref' => $referencia
            ]);

        } catch (Exception $e) {
            $this->redirect('home');
        }
    }
}
