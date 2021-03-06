<?php
class C_iuiphhk extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		session_start();
		if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if(!isset($_SESSION['USERID'])){
				$this->output->set_status_header('301');
			}
		}else{
			if(!isset($_SESSION['USERID'])){
				redirect('c_login');
			}
		}
		$this->load->model('m_iuiphhk');
		$this->load->model('m_iuiphhk_rencana_alat');
		$this->load->model('m_iuiphhk_rencana_produksi');
		$this->load->model('m_cek_list_iuiphhk');
	}
	
	function index(){
		$data["content"]	= $this->load->view('main/v_iuiphhk',"",true);
		$this->load->view("home",$data);
	}
	
	function switchAction(){
		$action = $this->input->post('action');
		switch($action){
			case 'GETLIST':
				$this->getList();
			break;
			case 'CREATE':
				$this->create();
			break;
			case 'UPDATE':
				$this->update();
			break;
			case 'DELETE':
				$this->delete();
			break;
			case 'SEARCH':
				$this->search();
			break;
			case 'PRINT':
				$this->printExcel();
			break;
			case 'GETSYARAT':
				$this->getSyarat();
			break;
			case 'GEiphhk_ALAT':
				$this->geiphhk_Alat();
			break;
			case 'GEiphhk_PRODUKSI':
				$this->geiphhk_Produksi();
			break;
			case 'CETAKLK':
				$this->printLK();
			break;
			case 'CETAKSK':
				$this->printSK();
			break;
			case 'UBAHPROSES':
				$this->ubahProses();
			break;
			case 'CETAKBP':
				$this->printBP();
			break;
			case 'EXCEL':
				$this->printExcel();
			break;
			default :
				echo '{ failure : true }';
			break;
		}
	}
	
	function getList(){
		$searchText = $this->input->post('query');
		$limit_start = (integer)$this->input->post('start');
		$limit_end = (integer)$this->input->post('limit');
		$params = array(
			'searchText' => $searchText,
			'limit_start' => $limit_start,
			'limit_end' => $limit_end
		);
		$result = $this->m_iuiphhk->getList($params);
		echo $result;
	}
	
	function create(){
		$params = json_decode($this->input->post('params'));
		extract(get_object_vars($params));
		
		$iphhk_author = $this->m_iuiphhk->__checkSession();
		$iphhk_created_date = date('Y-m-d H:i:s');
		
		$noreg = $this->m_public_function->getNomorReg(22);
		$resultperusahaan = $this->m_iuiphhk->cuperusahaan($params);
		$pemohon = $this->m_iuiphhk->cupemohon($params);
		$resultpermohonan = $this->m_iuiphhk->cupermohonan($params, $pemohon, $noreg);
		
		if($iphhk_author == ''){
			$result = 'sessionExpired';
		}else{
			$data = array(
				'ID_IUIPHHK'=>$ID_IUIPHHK,
				'ID_PEMOHON'=>$pemohon,
				'ID_PERUSAHAAN'=>$resultperusahaan,
				'NO_SK_LAMA'=>$NO_SK_LAMA,
				'JENIS_PERMOHONAN'=>$JENIS_PERMOHONAN,
				'NAMA_PERUSAHAAN'=>$NAMA_PERUSAHAAN,
				'NPWP'=>$NPWP,
				'ALAMAT'=>$ALAMAT,
				'STATUS_MODAL'=>$STATUS_MODAL,
				'NAMA_NOTARIS'=>$NAMA_NOTARIS,
				'NO_AKTA'=>$NO_AKTA,
				'PENANGGUNG_JAWAB'=>$PENANGGUNG_JAWAB,
				'NAMA_DIREKSI'=>$NAMA_DIREKSI,
				'DEWAN_KOMISARIS'=>$DEWAN_KOMISARIS,
				'TUJUAN_PRODUKSI'=>$TUJUAN_PRODUKSI,
				'LOKASI_PABRIK'=>$LOKASI_PABRIK,
				'LUAS_TANAH'=>$LUAS_TANAH,
				'ALAMAT_PABRIK'=>$ALAMAT_PABRIK,
				'OLAH1_P_TAHUN'=>$OLAH1_P_TAHUN,
				'OLAH1_P_BULAN'=>$OLAH1_P_BULAN,
				'OLAH2_P_TAHUN'=>$OLAH2_P_TAHUN,
				'OLAH2_P_BULAN'=>$OLAH2_P_BULAN,
				'OLAH3_P_TAHUN'=>$OLAH3_P_TAHUN,
				'OLAH3_P_BULAN'=>$OLAH3_P_BULAN,
				'OLAH1_S_TAHUN'=>$OLAH1_S_TAHUN,
				'OLAH1_S_BULAN'=>$OLAH1_S_BULAN,
				'OLAH2_S_TAHUN'=>$OLAH2_S_TAHUN,
				'OLAH2_S_BULAN'=>$OLAH2_S_BULAN,
				'OLAH3_S_TAHUN'=>$OLAH3_S_TAHUN,
				'OLAH3_S_BULAN'=>$OLAH3_S_BULAN,
				'MT_TANAH'=>$MT_TANAH,
				'MT_BANGUNAN'=>$MT_BANGUNAN,
				'MT_MESIN'=>$MT_MESIN,
				'MT_DLL'=>$MT_DLL,
				'MK_BAHAN_BAKU'=>$MK_BAHAN_BAKU,
				'MK_UPAH'=>$MK_UPAH,
				'MK_DLL'=>$MK_DLL,
				'SP_MODAL_SENDIRI'=>$SP_MODAL_SENDIRI,
				'SP_PINJAMAN'=>$SP_PINJAMAN,
				'TKI_L_JUMLAH'=>$TKI_L_JUMLAH,
				'TKI_P_JUMLAH'=>$TKI_P_JUMLAH,
				'TKA_JUMLAH'=>$TKA_JUMLAH,
				'TKA_ASAL'=>$TKA_ASAL,
				'TKA_JABATAN'=>$TKA_JABATAN,
				'TKA_JANGKA_WAKTU'=>$TKA_JANGKA_WAKTU,
				'DN_JENIS_PRODUK1'=>$DN_JENIS_PRODUK1,
				'DN_JENIS_PRODUK2'=>$DN_JENIS_PRODUK2,
				'DN_JENIS_PRODUK3'=>$DN_JENIS_PRODUK3,
				'E_JENIS_PRODUK1'=>$E_JENIS_PRODUK1,
				'E_JENIS_PRODUK2'=>$E_JENIS_PRODUK2,
				'E_JENIS_PRODUK3'=>$E_JENIS_PRODUK3,
				'MERK_JENIS_PRODUK'=>$MERK_JENIS_PRODUK,
				'BBKB_DN_JUMLAH'=>$BBKB_DN_JUMLAH,
				'BBKB_DN_SATUAN'=>$BBKB_DN_SATUAN,
				'BBKB_DN_ASAL'=>$BBKB_DN_ASAL,
				'BBKB_DN_HARGA'=>$BBKB_DN_HARGA,
				'BBKB_DN_KETERANGAN'=>$BBKB_DN_KETERANGAN,
				'BBKO_DN_JUMLAH'=>$BBKO_DN_JUMLAH,
				'BBKO_DN_SATUAN'=>$BBKO_DN_SATUAN,
				'BBKO_DN_ASAL'=>$BBKO_DN_ASAL,
				'BBKO_DN_HARGA'=>$BBKO_DN_HARGA,
				'BBKO_DN_KETERANGAN'=>$BBKO_DN_KETERANGAN,
				'BP_DN_JUMLAH'=>$BP_DN_JUMLAH,
				'BP_DN_SATUAN'=>$BP_DN_SATUAN,
				'BP_DN_ASAL'=>$BP_DN_ASAL,
				'BP_DN_HARGA'=>$BP_DN_HARGA,
				'BP_DN_KETERANGAN'=>$BP_DN_KETERANGAN,
				'BBKB_I_JUMLAH'=>$BBKB_I_JUMLAH,
				'BBKB_I_SATUAN'=>$BBKB_I_SATUAN,
				'BBKB_I_ASAL'=>$BBKB_I_ASAL,
				'BBKB_I_HARGA'=>$BBKB_I_HARGA,
				'BBKB_I_KETERANGAN'=>$BBKB_I_KETERANGAN,
				'BBKO_I_JUMLAH'=>$BBKO_I_JUMLAH,
				'BBKO_I_SATUAN'=>$BBKO_I_SATUAN,
				'BBKO_I_ASAL'=>$BBKO_I_ASAL,
				'BBKO_I_HARGA'=>$BBKO_I_HARGA,
				'BBKO_I_KETERANGAN'=>$BBKO_I_KETERANGAN,
				'BP_I_JUMLAH'=>$BP_I_JUMLAH,
				'BP_I_SATUAN'=>$BP_I_SATUAN,
				'BP_I_ASAL'=>$BP_I_ASAL,
				'BP_I_HARGA'=>$BP_I_HARGA,
				'BP_I_KETERANGAN'=>$BP_I_KETERANGAN,
				'RBB_LUAS_GUDANG'=>$RBB_LUAS_GUDANG,
				'RBB_KAYU_OLAHAN'=>$RBB_KAYU_OLAHAN,
				'RBB_PENOLONG'=>$RBB_PENOLONG,
				'RBB_HASIL_PRODUKSI'=>$RBB_HASIL_PRODUKSI,
				'RLPLY_LOKASI'=>$RLPLY_LOKASI,
				'RLPLY_LUAS'=>$RLPLY_LUAS,
				'RLPLY_PERIZINAN'=>$RLPLY_PERIZINAN,
				'RSD1_KAPASITAS'=>$RSD1_KAPASITAS,
				'RSD1_JUMLAH'=>$RSD1_JUMLAH,
				'RSD211_KAPASITAS'=>$RSD211_KAPASITAS,
				'RSD211_JUMLAH'=>$RSD211_JUMLAH,
				'RSD212_KAPASITAS'=>$RSD212_KAPASITAS,
				'RSD212_JUMLAH'=>$RSD212_JUMLAH,
				'RSD213_KAPASITAS'=>$RSD213_KAPASITAS,
				'RSD213_JUMLAH'=>$RSD213_JUMLAH,
				'RSD22_KAPASITAS'=>$RSD22_KAPASITAS,
				'RSD22_JUMLAH'=>$RSD22_JUMLAH,
				'RSD23_KAPASITAS'=>$RSD23_KAPASITAS,
				'RSD23_JUMLAH'=>$RSD23_JUMLAH,
				'RPL1_VOLUME'=>$RPL1_VOLUME,
				'RPL1_SATUAN'=>$RPL1_SATUAN,
				'RPL1_PENANGANAN'=>$RPL1_PENANGANAN,
				'RPL2_VOLUME'=>$RPL2_VOLUME,
				'RPL2_SATUAN'=>$RPL2_SATUAN,
				'RPL2_PENANGANAN'=>$RPL2_PENANGANAN,
				'RPL3_VOLUME'=>$RPL3_VOLUME,
				'RPL3_SATUAN'=>$RPL3_SATUAN,
				'RPL3_PENANGANAN'=>$RPL3_PENANGANAN,
				'RPL4_VOLUME'=>$RPL4_VOLUME,
				'RPL4_SATUAN'=>$RPL4_SATUAN,
				'RPL4_PENANGANAN'=>$RPL4_PENANGANAN,
				'PENYETUJU'=>$PENYETUJU,
				'NOMOR_SURAT'=>$NOMOR_SURAT,
				'TGL_TERLAMPIR'=>$TGL_TERLAMPIR,
				'TGL_PERMOHONAN'=>date("Y-m-d"),
				'STATUS_SURVEY'=>$STATUS_SURVEY,
				'STATUS'=>$STATUS,
				'TGL_BERLAKU'=>$TGL_BERLAKU,
				'TGL_BERAKHIR'=>$TGL_BERAKHIR,
			);
			$result = $this->m_iuiphhk->__insert($data, '', 'insertId');
			$iuiphhk_ket = json_decode($this->input->post('KETERANGAN_SYARAT'));
			$syarat = $this->m_iuiphhk->getSyarat2();
			$i=0;
			foreach($syarat as $row){
				$datacek = array(
				"ID_IJIN"=>$result,
				"ID_SYARAT"=>$row["ID_SYARAT"],
				"KETERANGAN"=>$iuiphhk_ket[$i]);
				$i++;
				$this->m_iuiphhk->__insert($datacek, 'cek_list_iuiphhk', '');
			}
			echo "success";
		}
	}
	
	function update(){
		$params = json_decode($this->input->post('params'));
		extract(get_object_vars($params));
		
		$iphhk_updated_by = $this->m_iuiphhk->__checkSession();
		$iphhk_updated_date = date('Y-m-d H:i:s');
		$resultperusahaan = $this->m_sktr->cuperusahaan($params);
		$resultpemohon = $this->m_sktr->cupemohon($params);
		$resultpermohonan = $this->m_sktr->cupermohonan($params, $resultpemohon, '');
		
		if($iphhk_updated_by == ''){
			$result = 'sessionExpired';
		}else{
			$data = array(
				'ID_PEMOHON'=>$resultpemohon,
				'ID_PERUSAHAAN'=>$resultperusahaan,
				'NO_SK_LAMA'=>$NO_SK_LAMA,
				'JENIS_PERMOHONAN'=>$JENIS_PERMOHONAN,
				'NAMA_PERUSAHAAN'=>$NAMA_PERUSAHAAN,
				'NPWP'=>$NPWP,
				'ALAMAT'=>$ALAMAT,
				'STATUS_MODAL'=>$STATUS_MODAL,
				'NAMA_NOTARIS'=>$NAMA_NOTARIS,
				'NO_AKTA'=>$NO_AKTA,
				'PENANGGUNG_JAWAB'=>$PENANGGUNG_JAWAB,
				'NAMA_DIREKSI'=>$NAMA_DIREKSI,
				'DEWAN_KOMISARIS'=>$DEWAN_KOMISARIS,
				'TUJUAN_PRODUKSI'=>$TUJUAN_PRODUKSI,
				'LOKASI_PABRIK'=>$LOKASI_PABRIK,
				'LUAS_TANAH'=>$LUAS_TANAH,
				'ALAMAT_PABRIK'=>$ALAMAT_PABRIK,
				'OLAH1_P_TAHUN'=>$OLAH1_P_TAHUN,
				'OLAH1_P_BULAN'=>$OLAH1_P_BULAN,
				'OLAH2_P_TAHUN'=>$OLAH2_P_TAHUN,
				'OLAH2_P_BULAN'=>$OLAH2_P_BULAN,
				'OLAH3_P_TAHUN'=>$OLAH3_P_TAHUN,
				'OLAH3_P_BULAN'=>$OLAH3_P_BULAN,
				'OLAH1_S_TAHUN'=>$OLAH1_S_TAHUN,
				'OLAH1_S_BULAN'=>$OLAH1_S_BULAN,
				'OLAH2_S_TAHUN'=>$OLAH2_S_TAHUN,
				'OLAH2_S_BULAN'=>$OLAH2_S_BULAN,
				'OLAH3_S_TAHUN'=>$OLAH3_S_TAHUN,
				'OLAH3_S_BULAN'=>$OLAH3_S_BULAN,
				'MT_TANAH'=>$MT_TANAH,
				'MT_BANGUNAN'=>$MT_BANGUNAN,
				'MT_MESIN'=>$MT_MESIN,
				'MT_DLL'=>$MT_DLL,
				'MK_BAHAN_BAKU'=>$MK_BAHAN_BAKU,
				'MK_UPAH'=>$MK_UPAH,
				'MK_DLL'=>$MK_DLL,
				'SP_MODAL_SENDIRI'=>$SP_MODAL_SENDIRI,
				'SP_PINJAMAN'=>$SP_PINJAMAN,
				'TKI_L_JUMLAH'=>$TKI_L_JUMLAH,
				'TKI_P_JUMLAH'=>$TKI_P_JUMLAH,
				'TKA_JUMLAH'=>$TKA_JUMLAH,
				'TKA_ASAL'=>$TKA_ASAL,
				'TKA_JABATAN'=>$TKA_JABATAN,
				'TKA_JANGKA_WAKTU'=>$TKA_JANGKA_WAKTU,
				'DN_JENIS_PRODUK1'=>$DN_JENIS_PRODUK1,
				'DN_JENIS_PRODUK2'=>$DN_JENIS_PRODUK2,
				'DN_JENIS_PRODUK3'=>$DN_JENIS_PRODUK3,
				'E_JENIS_PRODUK1'=>$E_JENIS_PRODUK1,
				'E_JENIS_PRODUK2'=>$E_JENIS_PRODUK2,
				'E_JENIS_PRODUK3'=>$E_JENIS_PRODUK3,
				'MERK_JENIS_PRODUK'=>$MERK_JENIS_PRODUK,
				'BBKB_DN_JUMLAH'=>$BBKB_DN_JUMLAH,
				'BBKB_DN_SATUAN'=>$BBKB_DN_SATUAN,
				'BBKB_DN_ASAL'=>$BBKB_DN_ASAL,
				'BBKB_DN_HARGA'=>$BBKB_DN_HARGA,
				'BBKB_DN_KETERANGAN'=>$BBKB_DN_KETERANGAN,
				'BBKO_DN_JUMLAH'=>$BBKO_DN_JUMLAH,
				'BBKO_DN_SATUAN'=>$BBKO_DN_SATUAN,
				'BBKO_DN_ASAL'=>$BBKO_DN_ASAL,
				'BBKO_DN_HARGA'=>$BBKO_DN_HARGA,
				'BBKO_DN_KETERANGAN'=>$BBKO_DN_KETERANGAN,
				'BBKB_I_JUMLAH'=>$BBKB_I_JUMLAH,
				'BBKB_I_SATUAN'=>$BBKB_I_SATUAN,
				'BBKB_I_ASAL'=>$BBKB_I_ASAL,
				'BBKB_I_HARGA'=>$BBKB_I_HARGA,
				'BBKB_I_KETERANGAN'=>$BBKB_I_KETERANGAN,
				'BBKO_I_JUMLAH'=>$BBKO_I_JUMLAH,
				'BBKO_I_SATUAN'=>$BBKO_I_SATUAN,
				'BBKO_I_ASAL'=>$BBKO_I_ASAL,
				'BBKO_I_HARGA'=>$BBKO_I_HARGA,
				'BBKO_I_KETERANGAN'=>$BBKO_I_KETERANGAN,
				'BP_I_JUMLAH'=>$BP_I_JUMLAH,
				'BP_I_SATUAN'=>$BP_I_SATUAN,
				'BP_I_ASAL'=>$BP_I_ASAL,
				'BP_I_HARGA'=>$BP_I_HARGA,
				'BP_I_KETERANGAN'=>$BP_I_KETERANGAN,
				'BP_DN_JUMLAH'=>$BP_DN_JUMLAH,
				'BP_DN_SATUAN'=>$BP_DN_SATUAN,
				'BP_DN_ASAL'=>$BP_DN_ASAL,
				'BP_DN_HARGA'=>$BP_DN_HARGA,
				'BP_DN_KETERANGAN'=>$BP_DN_KETERANGAN,
				'RBB_LUAS_GUDANG'=>$RBB_LUAS_GUDANG,
				'RBB_KAYU_OLAHAN'=>$RBB_KAYU_OLAHAN,
				'RBB_PENOLONG'=>$RBB_PENOLONG,
				'RBB_HASIL_PRODUKSI'=>$RBB_HASIL_PRODUKSI,
				'RLPLY_LOKASI'=>$RLPLY_LOKASI,
				'RLPLY_LUAS'=>$RLPLY_LUAS,
				'RLPLY_PERIZINAN'=>$RLPLY_PERIZINAN,
				'RSD1_KAPASITAS'=>$RSD1_KAPASITAS,
				'RSD1_JUMLAH'=>$RSD1_JUMLAH,
				'RSD211_KAPASITAS'=>$RSD211_KAPASITAS,
				'RSD211_JUMLAH'=>$RSD211_JUMLAH,
				'RSD212_KAPASITAS'=>$RSD212_KAPASITAS,
				'RSD212_JUMLAH'=>$RSD212_JUMLAH,
				'RSD213_KAPASITAS'=>$RSD213_KAPASITAS,
				'RSD213_JUMLAH'=>$RSD213_JUMLAH,
				'RSD22_KAPASITAS'=>$RSD22_KAPASITAS,
				'RSD22_JUMLAH'=>$RSD22_JUMLAH,
				'RSD23_KAPASITAS'=>$RSD23_KAPASITAS,
				'RSD23_JUMLAH'=>$RSD23_JUMLAH,
				'RPL1_VOLUME'=>$RPL1_VOLUME,
				'RPL1_SATUAN'=>$RPL1_SATUAN,
				'RPL1_PENANGANAN'=>$RPL1_PENANGANAN,
				'RPL2_VOLUME'=>$RPL2_VOLUME,
				'RPL2_SATUAN'=>$RPL2_SATUAN,
				'RPL2_PENANGANAN'=>$RPL2_PENANGANAN,
				'RPL3_VOLUME'=>$RPL3_VOLUME,
				'RPL3_SATUAN'=>$RPL3_SATUAN,
				'RPL3_PENANGANAN'=>$RPL3_PENANGANAN,
				'RPL4_VOLUME'=>$RPL4_VOLUME,
				'RPL4_SATUAN'=>$RPL4_SATUAN,
				'RPL4_PENANGANAN'=>$RPL4_PENANGANAN,
				'PENYETUJU'=>$PENYETUJU,
				'NOMOR_SURAT'=>$NOMOR_SURAT,
				'TGL_TERLAMPIR'=>$TGL_TERLAMPIR,
				'STATUS_SURVEY'=>$STATUS_SURVEY,
				'STATUS'=>$STATUS,
				'TGL_BERLAKU'=>$TGL_BERLAKU,
				'TGL_BERAKHIR'=>$TGL_BERAKHIR,
				);
			$result = $this->m_iuiphhk->__update($data, $ID_IUIPHHK, '', 'insertId');
			$iuiphhk_ket = json_decode($this->input->post('KETERANGAN_SYARAT'));
			$syarat = $this->m_iuiphhk->getSyarat2();
			$this->m_cek_list_iuiphhk->delete($ID_IUIPHHK);
			$i=0;
			foreach($syarat as $row){
				$datacek = array(
				"ID_IJIN"=>$ID_IUIPHHK,
				"ID_SYARAT"=>$row["ID_SYARAT"],
				"KETERANGAN"=>$iuiphhk_ket[$i]);
				$i++;
				$this->m_iuiphhk->__insert($datacek, 'cek_list_iuiphhk', '');
			}
			echo "success";
		}
	}
	
	function delete(){
		$ids = $this->input->post('ids');
		$arrayId = json_decode($ids);
		$result = $this->m_iuiphhk->__delete($arrayId,'');
		echo $result;
	}
	
	function search(){
		$limit_start = (integer)$this->input->post('start');
		$limit_end = (integer)$this->input->post('limit');
		$NO_SK = htmlentities($this->input->post('NO_SK'),ENT_QUOTES);
		$NO_SK_LAMA = htmlentities($this->input->post('NO_SK_LAMA'),ENT_QUOTES);
		$JENIS_PERMOHONAN = htmlentities($this->input->post('JENIS_PERMOHONAN'),ENT_QUOTES);
		$JENIS_PERMOHONAN = is_numeric($JENIS_PERMOHONAN) ? $JENIS_PERMOHONAN : 0;
		$NAMA_PERUSAHAAN = htmlentities($this->input->post('NAMA_PERUSAHAAN'),ENT_QUOTES);
		$NPWP = htmlentities($this->input->post('NPWP'),ENT_QUOTES);
		$ALAMAT = htmlentities($this->input->post('ALAMAT'),ENT_QUOTES);
		$STATUS_MODAL = htmlentities($this->input->post('STATUS_MODAL'),ENT_QUOTES);
		$STATUS_MODAL = is_numeric($STATUS_MODAL) ? $STATUS_MODAL : 0;
		$NAMA_NOTARIS = htmlentities($this->input->post('NAMA_NOTARIS'),ENT_QUOTES);
		$NO_AKTA = htmlentities($this->input->post('NO_AKTA'),ENT_QUOTES);
		$PENANGGUNG_JAWAB = htmlentities($this->input->post('PENANGGUNG_JAWAB'),ENT_QUOTES);
		$NAMA_DIREKSI = htmlentities($this->input->post('NAMA_DIREKSI'),ENT_QUOTES);
		$DEWAN_KOMISARIS = htmlentities($this->input->post('DEWAN_KOMISARIS'),ENT_QUOTES);
		$TUJUAN_PRODUKSI = htmlentities($this->input->post('TUJUAN_PRODUKSI'),ENT_QUOTES);
		$LOKASI_PABRIK = htmlentities($this->input->post('LOKASI_PABRIK'),ENT_QUOTES);
		$LOKASI_PABRIK = is_numeric($LOKASI_PABRIK) ? $LOKASI_PABRIK : 0;
		$LUAS_TANAH = htmlentities($this->input->post('LUAS_TANAH'),ENT_QUOTES);
		$LUAS_TANAH = is_numeric($LUAS_TANAH) ? $LUAS_TANAH : 0;
		$ALAMAT_PABRIK = htmlentities($this->input->post('ALAMAT_PABRIK'),ENT_QUOTES);
		$OLAH1_P_TAHUN = htmlentities($this->input->post('OLAH1_P_TAHUN'),ENT_QUOTES);
		$OLAH1_P_TAHUN = is_numeric($OLAH1_P_TAHUN) ? $OLAH1_P_TAHUN : 0;
		$OLAH1_P_BULAN = htmlentities($this->input->post('OLAH1_P_BULAN'),ENT_QUOTES);
		$OLAH1_P_BULAN = is_numeric($OLAH1_P_BULAN) ? $OLAH1_P_BULAN : 0;
		$OLAH2_P_TAHUN = htmlentities($this->input->post('OLAH2_P_TAHUN'),ENT_QUOTES);
		$OLAH2_P_TAHUN = is_numeric($OLAH2_P_TAHUN) ? $OLAH2_P_TAHUN : 0;
		$OLAH2_P_BULAN = htmlentities($this->input->post('OLAH2_P_BULAN'),ENT_QUOTES);
		$OLAH2_P_BULAN = is_numeric($OLAH2_P_BULAN) ? $OLAH2_P_BULAN : 0;
		$OLAH3_P_TAHUN = htmlentities($this->input->post('OLAH3_P_TAHUN'),ENT_QUOTES);
		$OLAH3_P_TAHUN = is_numeric($OLAH3_P_TAHUN) ? $OLAH3_P_TAHUN : 0;
		$OLAH3_P_BULAN = htmlentities($this->input->post('OLAH3_P_BULAN'),ENT_QUOTES);
		$OLAH3_P_BULAN = is_numeric($OLAH3_P_BULAN) ? $OLAH3_P_BULAN : 0;
		$OLAH1_S_TAHUN = htmlentities($this->input->post('OLAH1_S_TAHUN'),ENT_QUOTES);
		$OLAH1_S_TAHUN = is_numeric($OLAH1_S_TAHUN) ? $OLAH1_S_TAHUN : 0;
		$OLAH1_S_BULAN = htmlentities($this->input->post('OLAH1_S_BULAN'),ENT_QUOTES);
		$OLAH1_S_BULAN = is_numeric($OLAH1_S_BULAN) ? $OLAH1_S_BULAN : 0;
		$OLAH2_S_TAHUN = htmlentities($this->input->post('OLAH2_S_TAHUN'),ENT_QUOTES);
		$OLAH2_S_TAHUN = is_numeric($OLAH2_S_TAHUN) ? $OLAH2_S_TAHUN : 0;
		$OLAH2_S_BULAN = htmlentities($this->input->post('OLAH2_S_BULAN'),ENT_QUOTES);
		$OLAH2_S_BULAN = is_numeric($OLAH2_S_BULAN) ? $OLAH2_S_BULAN : 0;
		$OLAH3_S_TAHUN = htmlentities($this->input->post('OLAH3_S_TAHUN'),ENT_QUOTES);
		$OLAH3_S_TAHUN = is_numeric($OLAH3_S_TAHUN) ? $OLAH3_S_TAHUN : 0;
		$OLAH3_S_BULAN = htmlentities($this->input->post('OLAH3_S_BULAN'),ENT_QUOTES);
		$OLAH3_S_BULAN = is_numeric($OLAH3_S_BULAN) ? $OLAH3_S_BULAN : 0;
		$MT_TANAH = htmlentities($this->input->post('MT_TANAH'),ENT_QUOTES);
		$MT_TANAH = is_numeric($MT_TANAH) ? $MT_TANAH : 0;
		$MT_BANGUNAN = htmlentities($this->input->post('MT_BANGUNAN'),ENT_QUOTES);
		$MT_BANGUNAN = is_numeric($MT_BANGUNAN) ? $MT_BANGUNAN : 0;
		$MT_MESIN = htmlentities($this->input->post('MT_MESIN'),ENT_QUOTES);
		$MT_MESIN = is_numeric($MT_MESIN) ? $MT_MESIN : 0;
		$MT_DLL = htmlentities($this->input->post('MT_DLL'),ENT_QUOTES);
		$MT_DLL = is_numeric($MT_DLL) ? $MT_DLL : 0;
		$MK_BAHAN_BAKU = htmlentities($this->input->post('MK_BAHAN_BAKU'),ENT_QUOTES);
		$MK_BAHAN_BAKU = is_numeric($MK_BAHAN_BAKU) ? $MK_BAHAN_BAKU : 0;
		$MK_UPAH = htmlentities($this->input->post('MK_UPAH'),ENT_QUOTES);
		$MK_UPAH = is_numeric($MK_UPAH) ? $MK_UPAH : 0;
		$MK_DLL = htmlentities($this->input->post('MK_DLL'),ENT_QUOTES);
		$MK_DLL = is_numeric($MK_DLL) ? $MK_DLL : 0;
		$SP_MODAL_SENDIRI = htmlentities($this->input->post('SP_MODAL_SENDIRI'),ENT_QUOTES);
		$SP_MODAL_SENDIRI = is_numeric($SP_MODAL_SENDIRI) ? $SP_MODAL_SENDIRI : 0;
		$SP_PINJAMAN = htmlentities($this->input->post('SP_PINJAMAN'),ENT_QUOTES);
		$SP_PINJAMAN = is_numeric($SP_PINJAMAN) ? $SP_PINJAMAN : 0;
		$TKI_L_JUMLAH = htmlentities($this->input->post('TKI_L_JUMLAH'),ENT_QUOTES);
		$TKI_L_JUMLAH = is_numeric($TKI_L_JUMLAH) ? $TKI_L_JUMLAH : 0;
		$TKI_P_JUMLAH = htmlentities($this->input->post('TKI_P_JUMLAH'),ENT_QUOTES);
		$TKI_P_JUMLAH = is_numeric($TKI_P_JUMLAH) ? $TKI_P_JUMLAH : 0;
		$TKA_JUMLAH = htmlentities($this->input->post('TKA_JUMLAH'),ENT_QUOTES);
		$TKA_JUMLAH = is_numeric($TKA_JUMLAH) ? $TKA_JUMLAH : 0;
		$TKA_ASAL = htmlentities($this->input->post('TKA_ASAL'),ENT_QUOTES);
		$TKA_JABATAN = htmlentities($this->input->post('TKA_JABATAN'),ENT_QUOTES);
		$TKA_JANGKA_WAKTU = htmlentities($this->input->post('TKA_JANGKA_WAKTU'),ENT_QUOTES);
		$TKA_JANGKA_WAKTU = is_numeric($TKA_JANGKA_WAKTU) ? $TKA_JANGKA_WAKTU : 0;
		$DN_JENIS_PRODUK1 = htmlentities($this->input->post('DN_JENIS_PRODUK1'),ENT_QUOTES);
		$DN_JENIS_PRODUK1 = is_numeric($DN_JENIS_PRODUK1) ? $DN_JENIS_PRODUK1 : 0;
		$DN_JENIS_PRODUK2 = htmlentities($this->input->post('DN_JENIS_PRODUK2'),ENT_QUOTES);
		$DN_JENIS_PRODUK2 = is_numeric($DN_JENIS_PRODUK2) ? $DN_JENIS_PRODUK2 : 0;
		$DN_JENIS_PRODUK3 = htmlentities($this->input->post('DN_JENIS_PRODUK3'),ENT_QUOTES);
		$DN_JENIS_PRODUK3 = is_numeric($DN_JENIS_PRODUK3) ? $DN_JENIS_PRODUK3 : 0;
		$E_JENIS_PRODUK1 = htmlentities($this->input->post('E_JENIS_PRODUK1'),ENT_QUOTES);
		$E_JENIS_PRODUK1 = is_numeric($E_JENIS_PRODUK1) ? $E_JENIS_PRODUK1 : 0;
		$E_JENIS_PRODUK2 = htmlentities($this->input->post('E_JENIS_PRODUK2'),ENT_QUOTES);
		$E_JENIS_PRODUK2 = is_numeric($E_JENIS_PRODUK2) ? $E_JENIS_PRODUK2 : 0;
		$E_JENIS_PRODUK3 = htmlentities($this->input->post('E_JENIS_PRODUK3'),ENT_QUOTES);
		$E_JENIS_PRODUK3 = is_numeric($E_JENIS_PRODUK3) ? $E_JENIS_PRODUK3 : 0;
		$MERK_JENIS_PRODUK = htmlentities($this->input->post('MERK_JENIS_PRODUK'),ENT_QUOTES);
		$MERK_JENIS_PRODUK = is_numeric($MERK_JENIS_PRODUK) ? $MERK_JENIS_PRODUK : 0;
		$BBKB_DN_JUMLAH = htmlentities($this->input->post('BBKB_DN_JUMLAH'),ENT_QUOTES);
		$BBKB_DN_JUMLAH = is_numeric($BBKB_DN_JUMLAH) ? $BBKB_DN_JUMLAH : 0;
		$BBKB_DN_SATUAN = htmlentities($this->input->post('BBKB_DN_SATUAN'),ENT_QUOTES);
		$BBKB_DN_ASAL = htmlentities($this->input->post('BBKB_DN_ASAL'),ENT_QUOTES);
		$BBKB_DN_HARGA = htmlentities($this->input->post('BBKB_DN_HARGA'),ENT_QUOTES);
		$BBKB_DN_HARGA = is_numeric($BBKB_DN_HARGA) ? $BBKB_DN_HARGA : 0;
		$BBKB_DN_KETERANGAN = htmlentities($this->input->post('BBKB_DN_KETERANGAN'),ENT_QUOTES);
		$BBKO_DN_JUMLAH = htmlentities($this->input->post('BBKO_DN_JUMLAH'),ENT_QUOTES);
		$BBKO_DN_JUMLAH = is_numeric($BBKO_DN_JUMLAH) ? $BBKO_DN_JUMLAH : 0;
		$BBKO_DN_SATUAN = htmlentities($this->input->post('BBKO_DN_SATUAN'),ENT_QUOTES);
		$BBKO_DN_ASAL = htmlentities($this->input->post('BBKO_DN_ASAL'),ENT_QUOTES);
		$BBKO_DN_HARGA = htmlentities($this->input->post('BBKO_DN_HARGA'),ENT_QUOTES);
		$BBKO_DN_HARGA = is_numeric($BBKO_DN_HARGA) ? $BBKO_DN_HARGA : 0;
		$BBKO_DN_KETERANGAN = htmlentities($this->input->post('BBKO_DN_KETERANGAN'),ENT_QUOTES);
		$BP_DN_JUMLAH = htmlentities($this->input->post('BP_DN_JUMLAH'),ENT_QUOTES);
		$BP_DN_JUMLAH = is_numeric($BP_DN_JUMLAH) ? $BP_DN_JUMLAH : 0;
		$BP_DN_SATUAN = htmlentities($this->input->post('BP_DN_SATUAN'),ENT_QUOTES);
		$BP_DN_ASAL = htmlentities($this->input->post('BP_DN_ASAL'),ENT_QUOTES);
		$BP_DN_HARGA = htmlentities($this->input->post('BP_DN_HARGA'),ENT_QUOTES);
		$BP_DN_HARGA = is_numeric($BP_DN_HARGA) ? $BP_DN_HARGA : 0;
		$BP_DN_KETERANGAN = htmlentities($this->input->post('BP_DN_KETERANGAN'),ENT_QUOTES);
		$RBB_LUAS_GUDANG = htmlentities($this->input->post('RBB_LUAS_GUDANG'),ENT_QUOTES);
		$RBB_LUAS_GUDANG = is_numeric($RBB_LUAS_GUDANG) ? $RBB_LUAS_GUDANG : 0;
		$RBB_KAYU_OLAHAN = htmlentities($this->input->post('RBB_KAYU_OLAHAN'),ENT_QUOTES);
		$RBB_KAYU_OLAHAN = is_numeric($RBB_KAYU_OLAHAN) ? $RBB_KAYU_OLAHAN : 0;
		$RBB_PENOLONG = htmlentities($this->input->post('RBB_PENOLONG'),ENT_QUOTES);
		$RBB_PENOLONG = is_numeric($RBB_PENOLONG) ? $RBB_PENOLONG : 0;
		$RBB_HASIL_PRODUKSI = htmlentities($this->input->post('RBB_HASIL_PRODUKSI'),ENT_QUOTES);
		$RBB_HASIL_PRODUKSI = is_numeric($RBB_HASIL_PRODUKSI) ? $RBB_HASIL_PRODUKSI : 0;
		$RLPLY_LOKASI = htmlentities($this->input->post('RLPLY_LOKASI'),ENT_QUOTES);
		$RLPLY_LOKASI = is_numeric($RLPLY_LOKASI) ? $RLPLY_LOKASI : 0;
		$RLPLY_LUAS = htmlentities($this->input->post('RLPLY_LUAS'),ENT_QUOTES);
		$RLPLY_LUAS = is_numeric($RLPLY_LUAS) ? $RLPLY_LUAS : 0;
		$RLPLY_PERIZINAN = htmlentities($this->input->post('RLPLY_PERIZINAN'),ENT_QUOTES);
		$RLPLY_PERIZINAN = is_numeric($RLPLY_PERIZINAN) ? $RLPLY_PERIZINAN : 0;
		$RSD1_KAPASITAS = htmlentities($this->input->post('RSD1_KAPASITAS'),ENT_QUOTES);
		$RSD1_KAPASITAS = is_numeric($RSD1_KAPASITAS) ? $RSD1_KAPASITAS : 0;
		$RSD1_JUMLAH = htmlentities($this->input->post('RSD1_JUMLAH'),ENT_QUOTES);
		$RSD1_JUMLAH = is_numeric($RSD1_JUMLAH) ? $RSD1_JUMLAH : 0;
		$RSD211_KAPASITAS = htmlentities($this->input->post('RSD211_KAPASITAS'),ENT_QUOTES);
		$RSD211_KAPASITAS = is_numeric($RSD211_KAPASITAS) ? $RSD211_KAPASITAS : 0;
		$RSD211_JUMLAH = htmlentities($this->input->post('RSD211_JUMLAH'),ENT_QUOTES);
		$RSD211_JUMLAH = is_numeric($RSD211_JUMLAH) ? $RSD211_JUMLAH : 0;
		$RSD212_KAPASITAS = htmlentities($this->input->post('RSD212_KAPASITAS'),ENT_QUOTES);
		$RSD212_KAPASITAS = is_numeric($RSD212_KAPASITAS) ? $RSD212_KAPASITAS : 0;
		$RSD212_JUMLAH = htmlentities($this->input->post('RSD212_JUMLAH'),ENT_QUOTES);
		$RSD212_JUMLAH = is_numeric($RSD212_JUMLAH) ? $RSD212_JUMLAH : 0;
		$RSD213_KAPASITAS = htmlentities($this->input->post('RSD213_KAPASITAS'),ENT_QUOTES);
		$RSD213_KAPASITAS = is_numeric($RSD213_KAPASITAS) ? $RSD213_KAPASITAS : 0;
		$RSD213_JUMLAH = htmlentities($this->input->post('RSD213_JUMLAH'),ENT_QUOTES);
		$RSD213_JUMLAH = is_numeric($RSD213_JUMLAH) ? $RSD213_JUMLAH : 0;
		$RSD22_KAPASITAS = htmlentities($this->input->post('RSD22_KAPASITAS'),ENT_QUOTES);
		$RSD22_KAPASITAS = is_numeric($RSD22_KAPASITAS) ? $RSD22_KAPASITAS : 0;
		$RSD22_JUMLAH = htmlentities($this->input->post('RSD22_JUMLAH'),ENT_QUOTES);
		$RSD22_JUMLAH = is_numeric($RSD22_JUMLAH) ? $RSD22_JUMLAH : 0;
		$RSD23_KAPASITAS = htmlentities($this->input->post('RSD23_KAPASITAS'),ENT_QUOTES);
		$RSD23_KAPASITAS = is_numeric($RSD23_KAPASITAS) ? $RSD23_KAPASITAS : 0;
		$RSD23_JUMLAH = htmlentities($this->input->post('RSD23_JUMLAH'),ENT_QUOTES);
		$RSD23_JUMLAH = is_numeric($RSD23_JUMLAH) ? $RSD23_JUMLAH : 0;
		$RPL1_VOLUME = htmlentities($this->input->post('RPL1_VOLUME'),ENT_QUOTES);
		$RPL1_VOLUME = is_numeric($RPL1_VOLUME) ? $RPL1_VOLUME : 0;
		$RPL1_SATUAN = htmlentities($this->input->post('RPL1_SATUAN'),ENT_QUOTES);
		$RPL1_PENANGANAN = htmlentities($this->input->post('RPL1_PENANGANAN'),ENT_QUOTES);
		$RPL2_VOLUME = htmlentities($this->input->post('RPL2_VOLUME'),ENT_QUOTES);
		$RPL2_VOLUME = is_numeric($RPL2_VOLUME) ? $RPL2_VOLUME : 0;
		$RPL2_SATUAN = htmlentities($this->input->post('RPL2_SATUAN'),ENT_QUOTES);
		$RPL2_PENANGANAN = htmlentities($this->input->post('RPL2_PENANGANAN'),ENT_QUOTES);
		$RPL3_VOLUME = htmlentities($this->input->post('RPL3_VOLUME'),ENT_QUOTES);
		$RPL3_VOLUME = is_numeric($RPL3_VOLUME) ? $RPL3_VOLUME : 0;
		$RPL3_SATUAN = htmlentities($this->input->post('RPL3_SATUAN'),ENT_QUOTES);
		$RPL3_PENANGANAN = htmlentities($this->input->post('RPL3_PENANGANAN'),ENT_QUOTES);
		$RPL4_VOLUME = htmlentities($this->input->post('RPL4_VOLUME'),ENT_QUOTES);
		$RPL4_VOLUME = is_numeric($RPL4_VOLUME) ? $RPL4_VOLUME : 0;
		$RPL4_SATUAN = htmlentities($this->input->post('RPL4_SATUAN'),ENT_QUOTES);
		$RPL4_PENANGANAN = htmlentities($this->input->post('RPL4_PENANGANAN'),ENT_QUOTES);
		$PENYETUJU = htmlentities($this->input->post('PENYETUJU'),ENT_QUOTES);
		$NOMOR_SURAT = htmlentities($this->input->post('NOMOR_SURAT'),ENT_QUOTES);
		$TGL_TERLAMPIR = htmlentities($this->input->post('TGL_TERLAMPIR'),ENT_QUOTES);
		$TGL_PERMOHONAN = htmlentities($this->input->post('TGL_PERMOHONAN'),ENT_QUOTES);
		$STATUS_SURVEY = htmlentities($this->input->post('STATUS_SURVEY'),ENT_QUOTES);
		$STATUS_SURVEY = is_numeric($STATUS_SURVEY) ? $STATUS_SURVEY : 0;
		$STATUS = htmlentities($this->input->post('STATUS'),ENT_QUOTES);
		$STATUS = is_numeric($STATUS) ? $STATUS : 0;
		$TGL_BERLAKU = htmlentities($this->input->post('TGL_BERLAKU'),ENT_QUOTES);
		$TGL_BERAKHIR = htmlentities($this->input->post('TGL_BERAKHIR'),ENT_QUOTES);
				
		$params = array(
			'NO_SK'=>$NO_SK,
			'NO_SK_LAMA'=>$NO_SK_LAMA,
			'JENIS_PERMOHONAN'=>$JENIS_PERMOHONAN,
			'NAMA_PERUSAHAAN'=>$NAMA_PERUSAHAAN,
			'NPWP'=>$NPWP,
			'ALAMAT'=>$ALAMAT,
			'STATUS_MODAL'=>$STATUS_MODAL,
			'NAMA_NOTARIS'=>$NAMA_NOTARIS,
			'NO_AKTA'=>$NO_AKTA,
			'PENANGGUNG_JAWAB'=>$PENANGGUNG_JAWAB,
			'NAMA_DIREKSI'=>$NAMA_DIREKSI,
			'DEWAN_KOMISARIS'=>$DEWAN_KOMISARIS,
			'TUJUAN_PRODUKSI'=>$TUJUAN_PRODUKSI,
			'LOKASI_PABRIK'=>$LOKASI_PABRIK,
			'LUAS_TANAH'=>$LUAS_TANAH,
			'ALAMAT_PABRIK'=>$ALAMAT_PABRIK,
			'OLAH1_P_TAHUN'=>$OLAH1_P_TAHUN,
			'OLAH1_P_BULAN'=>$OLAH1_P_BULAN,
			'OLAH2_P_TAHUN'=>$OLAH2_P_TAHUN,
			'OLAH2_P_BULAN'=>$OLAH2_P_BULAN,
			'OLAH3_P_TAHUN'=>$OLAH3_P_TAHUN,
			'OLAH3_P_BULAN'=>$OLAH3_P_BULAN,
			'OLAH1_S_TAHUN'=>$OLAH1_S_TAHUN,
			'OLAH1_S_BULAN'=>$OLAH1_S_BULAN,
			'OLAH2_S_TAHUN'=>$OLAH2_S_TAHUN,
			'OLAH2_S_BULAN'=>$OLAH2_S_BULAN,
			'OLAH3_S_TAHUN'=>$OLAH3_S_TAHUN,
			'OLAH3_S_BULAN'=>$OLAH3_S_BULAN,
			'MT_TANAH'=>$MT_TANAH,
			'MT_BANGUNAN'=>$MT_BANGUNAN,
			'MT_MESIN'=>$MT_MESIN,
			'MT_DLL'=>$MT_DLL,
			'MK_BAHAN_BAKU'=>$MK_BAHAN_BAKU,
			'MK_UPAH'=>$MK_UPAH,
			'MK_DLL'=>$MK_DLL,
			'SP_MODAL_SENDIRI'=>$SP_MODAL_SENDIRI,
			'SP_PINJAMAN'=>$SP_PINJAMAN,
			'TKI_L_JUMLAH'=>$TKI_L_JUMLAH,
			'TKI_P_JUMLAH'=>$TKI_P_JUMLAH,
			'TKA_JUMLAH'=>$TKA_JUMLAH,
			'TKA_ASAL'=>$TKA_ASAL,
			'TKA_JABATAN'=>$TKA_JABATAN,
			'TKA_JANGKA_WAKTU'=>$TKA_JANGKA_WAKTU,
			'DN_JENIS_PRODUK1'=>$DN_JENIS_PRODUK1,
			'DN_JENIS_PRODUK2'=>$DN_JENIS_PRODUK2,
			'DN_JENIS_PRODUK3'=>$DN_JENIS_PRODUK3,
			'E_JENIS_PRODUK1'=>$E_JENIS_PRODUK1,
			'E_JENIS_PRODUK2'=>$E_JENIS_PRODUK2,
			'E_JENIS_PRODUK3'=>$E_JENIS_PRODUK3,
			'MERK_JENIS_PRODUK'=>$MERK_JENIS_PRODUK,
			'BBKB_DN_JUMLAH'=>$BBKB_DN_JUMLAH,
			'BBKB_DN_SATUAN'=>$BBKB_DN_SATUAN,
			'BBKB_DN_ASAL'=>$BBKB_DN_ASAL,
			'BBKB_DN_HARGA'=>$BBKB_DN_HARGA,
			'BBKB_DN_KETERANGAN'=>$BBKB_DN_KETERANGAN,
			'BBKO_DN_JUMLAH'=>$BBKO_DN_JUMLAH,
			'BBKO_DN_SATUAN'=>$BBKO_DN_SATUAN,
			'BBKO_DN_ASAL'=>$BBKO_DN_ASAL,
			'BBKO_DN_HARGA'=>$BBKO_DN_HARGA,
			'BBKO_DN_KETERANGAN'=>$BBKO_DN_KETERANGAN,
			'BP_DN_JUMLAH'=>$BP_DN_JUMLAH,
			'BP_DN_SATUAN'=>$BP_DN_SATUAN,
			'BP_DN_ASAL'=>$BP_DN_ASAL,
			'BP_DN_HARGA'=>$BP_DN_HARGA,
			'BP_DN_KETERANGAN'=>$BP_DN_KETERANGAN,
			'RBB_LUAS_GUDANG'=>$RBB_LUAS_GUDANG,
			'RBB_KAYU_OLAHAN'=>$RBB_KAYU_OLAHAN,
			'RBB_PENOLONG'=>$RBB_PENOLONG,
			'RBB_HASIL_PRODUKSI'=>$RBB_HASIL_PRODUKSI,
			'RLPLY_LOKASI'=>$RLPLY_LOKASI,
			'RLPLY_LUAS'=>$RLPLY_LUAS,
			'RLPLY_PERIZINAN'=>$RLPLY_PERIZINAN,
			'RSD1_KAPASITAS'=>$RSD1_KAPASITAS,
			'RSD1_JUMLAH'=>$RSD1_JUMLAH,
			'RSD211_KAPASITAS'=>$RSD211_KAPASITAS,
			'RSD211_JUMLAH'=>$RSD211_JUMLAH,
			'RSD212_KAPASITAS'=>$RSD212_KAPASITAS,
			'RSD212_JUMLAH'=>$RSD212_JUMLAH,
			'RSD213_KAPASITAS'=>$RSD213_KAPASITAS,
			'RSD213_JUMLAH'=>$RSD213_JUMLAH,
			'RSD22_KAPASITAS'=>$RSD22_KAPASITAS,
			'RSD22_JUMLAH'=>$RSD22_JUMLAH,
			'RSD23_KAPASITAS'=>$RSD23_KAPASITAS,
			'RSD23_JUMLAH'=>$RSD23_JUMLAH,
			'RPL1_VOLUME'=>$RPL1_VOLUME,
			'RPL1_SATUAN'=>$RPL1_SATUAN,
			'RPL1_PENANGANAN'=>$RPL1_PENANGANAN,
			'RPL2_VOLUME'=>$RPL2_VOLUME,
			'RPL2_SATUAN'=>$RPL2_SATUAN,
			'RPL2_PENANGANAN'=>$RPL2_PENANGANAN,
			'RPL3_VOLUME'=>$RPL3_VOLUME,
			'RPL3_SATUAN'=>$RPL3_SATUAN,
			'RPL3_PENANGANAN'=>$RPL3_PENANGANAN,
			'RPL4_VOLUME'=>$RPL4_VOLUME,
			'RPL4_SATUAN'=>$RPL4_SATUAN,
			'RPL4_PENANGANAN'=>$RPL4_PENANGANAN,
			'PENYETUJU'=>$PENYETUJU,
			'NOMOR_SURAT'=>$NOMOR_SURAT,
			'TGL_TERLAMPIR'=>$TGL_TERLAMPIR,
			'TGL_PERMOHONAN'=>$TGL_PERMOHONAN,
			'STATUS_SURVEY'=>$STATUS_SURVEY,
			'STATUS'=>$STATUS,
			'TGL_BERLAKU'=>$TGL_BERLAKU,
			'TGL_BERAKHIR'=>$TGL_BERAKHIR,
			'limit_start' => $limit_start,
			'limit_end' => $limit_end
		);
		
		$result = $this->m_iuiphhk->search($params);
		echo $result;
	}
	
	function printExcel(){
		$outputType = $this->input->post('action');
		
		$searchText = $this->input->post('query');
		$currentAction = $this->input->post('currentAction');
		$NO_SK = htmlentities($this->input->post('NO_SK'),ENT_QUOTES);
		$NO_SK_LAMA = htmlentities($this->input->post('NO_SK_LAMA'),ENT_QUOTES);
		$JENIS_PERMOHONAN = htmlentities($this->input->post('JENIS_PERMOHONAN'),ENT_QUOTES);
		$JENIS_PERMOHONAN = is_numeric($JENIS_PERMOHONAN) ? $JENIS_PERMOHONAN : 0;
		$NAMA_PERUSAHAAN = htmlentities($this->input->post('NAMA_PERUSAHAAN'),ENT_QUOTES);
		$NPWP = htmlentities($this->input->post('NPWP'),ENT_QUOTES);
		$ALAMAT = htmlentities($this->input->post('ALAMAT'),ENT_QUOTES);
		$STATUS_MODAL = htmlentities($this->input->post('STATUS_MODAL'),ENT_QUOTES);
		$STATUS_MODAL = is_numeric($STATUS_MODAL) ? $STATUS_MODAL : 0;
		$NAMA_NOTARIS = htmlentities($this->input->post('NAMA_NOTARIS'),ENT_QUOTES);
		$NO_AKTA = htmlentities($this->input->post('NO_AKTA'),ENT_QUOTES);
		$PENANGGUNG_JAWAB = htmlentities($this->input->post('PENANGGUNG_JAWAB'),ENT_QUOTES);
		$NAMA_DIREKSI = htmlentities($this->input->post('NAMA_DIREKSI'),ENT_QUOTES);
		$DEWAN_KOMISARIS = htmlentities($this->input->post('DEWAN_KOMISARIS'),ENT_QUOTES);
		$TUJUAN_PRODUKSI = htmlentities($this->input->post('TUJUAN_PRODUKSI'),ENT_QUOTES);
		$LOKASI_PABRIK = htmlentities($this->input->post('LOKASI_PABRIK'),ENT_QUOTES);
		$LOKASI_PABRIK = is_numeric($LOKASI_PABRIK) ? $LOKASI_PABRIK : 0;
		$LUAS_TANAH = htmlentities($this->input->post('LUAS_TANAH'),ENT_QUOTES);
		$LUAS_TANAH = is_numeric($LUAS_TANAH) ? $LUAS_TANAH : 0;
		$ALAMAT_PABRIK = htmlentities($this->input->post('ALAMAT_PABRIK'),ENT_QUOTES);
		$OLAH1_P_TAHUN = htmlentities($this->input->post('OLAH1_P_TAHUN'),ENT_QUOTES);
		$OLAH1_P_TAHUN = is_numeric($OLAH1_P_TAHUN) ? $OLAH1_P_TAHUN : 0;
		$OLAH1_P_BULAN = htmlentities($this->input->post('OLAH1_P_BULAN'),ENT_QUOTES);
		$OLAH1_P_BULAN = is_numeric($OLAH1_P_BULAN) ? $OLAH1_P_BULAN : 0;
		$OLAH2_P_TAHUN = htmlentities($this->input->post('OLAH2_P_TAHUN'),ENT_QUOTES);
		$OLAH2_P_TAHUN = is_numeric($OLAH2_P_TAHUN) ? $OLAH2_P_TAHUN : 0;
		$OLAH2_P_BULAN = htmlentities($this->input->post('OLAH2_P_BULAN'),ENT_QUOTES);
		$OLAH2_P_BULAN = is_numeric($OLAH2_P_BULAN) ? $OLAH2_P_BULAN : 0;
		$OLAH3_P_TAHUN = htmlentities($this->input->post('OLAH3_P_TAHUN'),ENT_QUOTES);
		$OLAH3_P_TAHUN = is_numeric($OLAH3_P_TAHUN) ? $OLAH3_P_TAHUN : 0;
		$OLAH3_P_BULAN = htmlentities($this->input->post('OLAH3_P_BULAN'),ENT_QUOTES);
		$OLAH3_P_BULAN = is_numeric($OLAH3_P_BULAN) ? $OLAH3_P_BULAN : 0;
		$OLAH1_S_TAHUN = htmlentities($this->input->post('OLAH1_S_TAHUN'),ENT_QUOTES);
		$OLAH1_S_TAHUN = is_numeric($OLAH1_S_TAHUN) ? $OLAH1_S_TAHUN : 0;
		$OLAH1_S_BULAN = htmlentities($this->input->post('OLAH1_S_BULAN'),ENT_QUOTES);
		$OLAH1_S_BULAN = is_numeric($OLAH1_S_BULAN) ? $OLAH1_S_BULAN : 0;
		$OLAH2_S_TAHUN = htmlentities($this->input->post('OLAH2_S_TAHUN'),ENT_QUOTES);
		$OLAH2_S_TAHUN = is_numeric($OLAH2_S_TAHUN) ? $OLAH2_S_TAHUN : 0;
		$OLAH2_S_BULAN = htmlentities($this->input->post('OLAH2_S_BULAN'),ENT_QUOTES);
		$OLAH2_S_BULAN = is_numeric($OLAH2_S_BULAN) ? $OLAH2_S_BULAN : 0;
		$OLAH3_S_TAHUN = htmlentities($this->input->post('OLAH3_S_TAHUN'),ENT_QUOTES);
		$OLAH3_S_TAHUN = is_numeric($OLAH3_S_TAHUN) ? $OLAH3_S_TAHUN : 0;
		$OLAH3_S_BULAN = htmlentities($this->input->post('OLAH3_S_BULAN'),ENT_QUOTES);
		$OLAH3_S_BULAN = is_numeric($OLAH3_S_BULAN) ? $OLAH3_S_BULAN : 0;
		$MT_TANAH = htmlentities($this->input->post('MT_TANAH'),ENT_QUOTES);
		$MT_TANAH = is_numeric($MT_TANAH) ? $MT_TANAH : 0;
		$MT_BANGUNAN = htmlentities($this->input->post('MT_BANGUNAN'),ENT_QUOTES);
		$MT_BANGUNAN = is_numeric($MT_BANGUNAN) ? $MT_BANGUNAN : 0;
		$MT_MESIN = htmlentities($this->input->post('MT_MESIN'),ENT_QUOTES);
		$MT_MESIN = is_numeric($MT_MESIN) ? $MT_MESIN : 0;
		$MT_DLL = htmlentities($this->input->post('MT_DLL'),ENT_QUOTES);
		$MT_DLL = is_numeric($MT_DLL) ? $MT_DLL : 0;
		$MK_BAHAN_BAKU = htmlentities($this->input->post('MK_BAHAN_BAKU'),ENT_QUOTES);
		$MK_BAHAN_BAKU = is_numeric($MK_BAHAN_BAKU) ? $MK_BAHAN_BAKU : 0;
		$MK_UPAH = htmlentities($this->input->post('MK_UPAH'),ENT_QUOTES);
		$MK_UPAH = is_numeric($MK_UPAH) ? $MK_UPAH : 0;
		$MK_DLL = htmlentities($this->input->post('MK_DLL'),ENT_QUOTES);
		$MK_DLL = is_numeric($MK_DLL) ? $MK_DLL : 0;
		$SP_MODAL_SENDIRI = htmlentities($this->input->post('SP_MODAL_SENDIRI'),ENT_QUOTES);
		$SP_MODAL_SENDIRI = is_numeric($SP_MODAL_SENDIRI) ? $SP_MODAL_SENDIRI : 0;
		$SP_PINJAMAN = htmlentities($this->input->post('SP_PINJAMAN'),ENT_QUOTES);
		$SP_PINJAMAN = is_numeric($SP_PINJAMAN) ? $SP_PINJAMAN : 0;
		$TKI_L_JUMLAH = htmlentities($this->input->post('TKI_L_JUMLAH'),ENT_QUOTES);
		$TKI_L_JUMLAH = is_numeric($TKI_L_JUMLAH) ? $TKI_L_JUMLAH : 0;
		$TKI_P_JUMLAH = htmlentities($this->input->post('TKI_P_JUMLAH'),ENT_QUOTES);
		$TKI_P_JUMLAH = is_numeric($TKI_P_JUMLAH) ? $TKI_P_JUMLAH : 0;
		$TKA_JUMLAH = htmlentities($this->input->post('TKA_JUMLAH'),ENT_QUOTES);
		$TKA_JUMLAH = is_numeric($TKA_JUMLAH) ? $TKA_JUMLAH : 0;
		$TKA_ASAL = htmlentities($this->input->post('TKA_ASAL'),ENT_QUOTES);
		$TKA_JABATAN = htmlentities($this->input->post('TKA_JABATAN'),ENT_QUOTES);
		$TKA_JANGKA_WAKTU = htmlentities($this->input->post('TKA_JANGKA_WAKTU'),ENT_QUOTES);
		$TKA_JANGKA_WAKTU = is_numeric($TKA_JANGKA_WAKTU) ? $TKA_JANGKA_WAKTU : 0;
		$DN_JENIS_PRODUK1 = htmlentities($this->input->post('DN_JENIS_PRODUK1'),ENT_QUOTES);
		$DN_JENIS_PRODUK1 = is_numeric($DN_JENIS_PRODUK1) ? $DN_JENIS_PRODUK1 : 0;
		$DN_JENIS_PRODUK2 = htmlentities($this->input->post('DN_JENIS_PRODUK2'),ENT_QUOTES);
		$DN_JENIS_PRODUK2 = is_numeric($DN_JENIS_PRODUK2) ? $DN_JENIS_PRODUK2 : 0;
		$DN_JENIS_PRODUK3 = htmlentities($this->input->post('DN_JENIS_PRODUK3'),ENT_QUOTES);
		$DN_JENIS_PRODUK3 = is_numeric($DN_JENIS_PRODUK3) ? $DN_JENIS_PRODUK3 : 0;
		$E_JENIS_PRODUK1 = htmlentities($this->input->post('E_JENIS_PRODUK1'),ENT_QUOTES);
		$E_JENIS_PRODUK1 = is_numeric($E_JENIS_PRODUK1) ? $E_JENIS_PRODUK1 : 0;
		$E_JENIS_PRODUK2 = htmlentities($this->input->post('E_JENIS_PRODUK2'),ENT_QUOTES);
		$E_JENIS_PRODUK2 = is_numeric($E_JENIS_PRODUK2) ? $E_JENIS_PRODUK2 : 0;
		$E_JENIS_PRODUK3 = htmlentities($this->input->post('E_JENIS_PRODUK3'),ENT_QUOTES);
		$E_JENIS_PRODUK3 = is_numeric($E_JENIS_PRODUK3) ? $E_JENIS_PRODUK3 : 0;
		$MERK_JENIS_PRODUK = htmlentities($this->input->post('MERK_JENIS_PRODUK'),ENT_QUOTES);
		$MERK_JENIS_PRODUK = is_numeric($MERK_JENIS_PRODUK) ? $MERK_JENIS_PRODUK : 0;
		$BBKB_DN_JUMLAH = htmlentities($this->input->post('BBKB_DN_JUMLAH'),ENT_QUOTES);
		$BBKB_DN_JUMLAH = is_numeric($BBKB_DN_JUMLAH) ? $BBKB_DN_JUMLAH : 0;
		$BBKB_DN_SATUAN = htmlentities($this->input->post('BBKB_DN_SATUAN'),ENT_QUOTES);
		$BBKB_DN_ASAL = htmlentities($this->input->post('BBKB_DN_ASAL'),ENT_QUOTES);
		$BBKB_DN_HARGA = htmlentities($this->input->post('BBKB_DN_HARGA'),ENT_QUOTES);
		$BBKB_DN_HARGA = is_numeric($BBKB_DN_HARGA) ? $BBKB_DN_HARGA : 0;
		$BBKB_DN_KETERANGAN = htmlentities($this->input->post('BBKB_DN_KETERANGAN'),ENT_QUOTES);
		$BBKO_DN_JUMLAH = htmlentities($this->input->post('BBKO_DN_JUMLAH'),ENT_QUOTES);
		$BBKO_DN_JUMLAH = is_numeric($BBKO_DN_JUMLAH) ? $BBKO_DN_JUMLAH : 0;
		$BBKO_DN_SATUAN = htmlentities($this->input->post('BBKO_DN_SATUAN'),ENT_QUOTES);
		$BBKO_DN_ASAL = htmlentities($this->input->post('BBKO_DN_ASAL'),ENT_QUOTES);
		$BBKO_DN_HARGA = htmlentities($this->input->post('BBKO_DN_HARGA'),ENT_QUOTES);
		$BBKO_DN_HARGA = is_numeric($BBKO_DN_HARGA) ? $BBKO_DN_HARGA : 0;
		$BBKO_DN_KETERANGAN = htmlentities($this->input->post('BBKO_DN_KETERANGAN'),ENT_QUOTES);
		$BP_DN_JUMLAH = htmlentities($this->input->post('BP_DN_JUMLAH'),ENT_QUOTES);
		$BP_DN_JUMLAH = is_numeric($BP_DN_JUMLAH) ? $BP_DN_JUMLAH : 0;
		$BP_DN_SATUAN = htmlentities($this->input->post('BP_DN_SATUAN'),ENT_QUOTES);
		$BP_DN_ASAL = htmlentities($this->input->post('BP_DN_ASAL'),ENT_QUOTES);
		$BP_DN_HARGA = htmlentities($this->input->post('BP_DN_HARGA'),ENT_QUOTES);
		$BP_DN_HARGA = is_numeric($BP_DN_HARGA) ? $BP_DN_HARGA : 0;
		$BP_DN_KETERANGAN = htmlentities($this->input->post('BP_DN_KETERANGAN'),ENT_QUOTES);
		$RBB_LUAS_GUDANG = htmlentities($this->input->post('RBB_LUAS_GUDANG'),ENT_QUOTES);
		$RBB_LUAS_GUDANG = is_numeric($RBB_LUAS_GUDANG) ? $RBB_LUAS_GUDANG : 0;
		$RBB_KAYU_OLAHAN = htmlentities($this->input->post('RBB_KAYU_OLAHAN'),ENT_QUOTES);
		$RBB_KAYU_OLAHAN = is_numeric($RBB_KAYU_OLAHAN) ? $RBB_KAYU_OLAHAN : 0;
		$RBB_PENOLONG = htmlentities($this->input->post('RBB_PENOLONG'),ENT_QUOTES);
		$RBB_PENOLONG = is_numeric($RBB_PENOLONG) ? $RBB_PENOLONG : 0;
		$RBB_HASIL_PRODUKSI = htmlentities($this->input->post('RBB_HASIL_PRODUKSI'),ENT_QUOTES);
		$RBB_HASIL_PRODUKSI = is_numeric($RBB_HASIL_PRODUKSI) ? $RBB_HASIL_PRODUKSI : 0;
		$RLPLY_LOKASI = htmlentities($this->input->post('RLPLY_LOKASI'),ENT_QUOTES);
		$RLPLY_LOKASI = is_numeric($RLPLY_LOKASI) ? $RLPLY_LOKASI : 0;
		$RLPLY_LUAS = htmlentities($this->input->post('RLPLY_LUAS'),ENT_QUOTES);
		$RLPLY_LUAS = is_numeric($RLPLY_LUAS) ? $RLPLY_LUAS : 0;
		$RLPLY_PERIZINAN = htmlentities($this->input->post('RLPLY_PERIZINAN'),ENT_QUOTES);
		$RLPLY_PERIZINAN = is_numeric($RLPLY_PERIZINAN) ? $RLPLY_PERIZINAN : 0;
		$RSD1_KAPASITAS = htmlentities($this->input->post('RSD1_KAPASITAS'),ENT_QUOTES);
		$RSD1_KAPASITAS = is_numeric($RSD1_KAPASITAS) ? $RSD1_KAPASITAS : 0;
		$RSD1_JUMLAH = htmlentities($this->input->post('RSD1_JUMLAH'),ENT_QUOTES);
		$RSD1_JUMLAH = is_numeric($RSD1_JUMLAH) ? $RSD1_JUMLAH : 0;
		$RSD211_KAPASITAS = htmlentities($this->input->post('RSD211_KAPASITAS'),ENT_QUOTES);
		$RSD211_KAPASITAS = is_numeric($RSD211_KAPASITAS) ? $RSD211_KAPASITAS : 0;
		$RSD211_JUMLAH = htmlentities($this->input->post('RSD211_JUMLAH'),ENT_QUOTES);
		$RSD211_JUMLAH = is_numeric($RSD211_JUMLAH) ? $RSD211_JUMLAH : 0;
		$RSD212_KAPASITAS = htmlentities($this->input->post('RSD212_KAPASITAS'),ENT_QUOTES);
		$RSD212_KAPASITAS = is_numeric($RSD212_KAPASITAS) ? $RSD212_KAPASITAS : 0;
		$RSD212_JUMLAH = htmlentities($this->input->post('RSD212_JUMLAH'),ENT_QUOTES);
		$RSD212_JUMLAH = is_numeric($RSD212_JUMLAH) ? $RSD212_JUMLAH : 0;
		$RSD213_KAPASITAS = htmlentities($this->input->post('RSD213_KAPASITAS'),ENT_QUOTES);
		$RSD213_KAPASITAS = is_numeric($RSD213_KAPASITAS) ? $RSD213_KAPASITAS : 0;
		$RSD213_JUMLAH = htmlentities($this->input->post('RSD213_JUMLAH'),ENT_QUOTES);
		$RSD213_JUMLAH = is_numeric($RSD213_JUMLAH) ? $RSD213_JUMLAH : 0;
		$RSD22_KAPASITAS = htmlentities($this->input->post('RSD22_KAPASITAS'),ENT_QUOTES);
		$RSD22_KAPASITAS = is_numeric($RSD22_KAPASITAS) ? $RSD22_KAPASITAS : 0;
		$RSD22_JUMLAH = htmlentities($this->input->post('RSD22_JUMLAH'),ENT_QUOTES);
		$RSD22_JUMLAH = is_numeric($RSD22_JUMLAH) ? $RSD22_JUMLAH : 0;
		$RSD23_KAPASITAS = htmlentities($this->input->post('RSD23_KAPASITAS'),ENT_QUOTES);
		$RSD23_KAPASITAS = is_numeric($RSD23_KAPASITAS) ? $RSD23_KAPASITAS : 0;
		$RSD23_JUMLAH = htmlentities($this->input->post('RSD23_JUMLAH'),ENT_QUOTES);
		$RSD23_JUMLAH = is_numeric($RSD23_JUMLAH) ? $RSD23_JUMLAH : 0;
		$RPL1_VOLUME = htmlentities($this->input->post('RPL1_VOLUME'),ENT_QUOTES);
		$RPL1_VOLUME = is_numeric($RPL1_VOLUME) ? $RPL1_VOLUME : 0;
		$RPL1_SATUAN = htmlentities($this->input->post('RPL1_SATUAN'),ENT_QUOTES);
		$RPL1_PENANGANAN = htmlentities($this->input->post('RPL1_PENANGANAN'),ENT_QUOTES);
		$RPL2_VOLUME = htmlentities($this->input->post('RPL2_VOLUME'),ENT_QUOTES);
		$RPL2_VOLUME = is_numeric($RPL2_VOLUME) ? $RPL2_VOLUME : 0;
		$RPL2_SATUAN = htmlentities($this->input->post('RPL2_SATUAN'),ENT_QUOTES);
		$RPL2_PENANGANAN = htmlentities($this->input->post('RPL2_PENANGANAN'),ENT_QUOTES);
		$RPL3_VOLUME = htmlentities($this->input->post('RPL3_VOLUME'),ENT_QUOTES);
		$RPL3_VOLUME = is_numeric($RPL3_VOLUME) ? $RPL3_VOLUME : 0;
		$RPL3_SATUAN = htmlentities($this->input->post('RPL3_SATUAN'),ENT_QUOTES);
		$RPL3_PENANGANAN = htmlentities($this->input->post('RPL3_PENANGANAN'),ENT_QUOTES);
		$RPL4_VOLUME = htmlentities($this->input->post('RPL4_VOLUME'),ENT_QUOTES);
		$RPL4_VOLUME = is_numeric($RPL4_VOLUME) ? $RPL4_VOLUME : 0;
		$RPL4_SATUAN = htmlentities($this->input->post('RPL4_SATUAN'),ENT_QUOTES);
		$RPL4_PENANGANAN = htmlentities($this->input->post('RPL4_PENANGANAN'),ENT_QUOTES);
		$PENYETUJU = htmlentities($this->input->post('PENYETUJU'),ENT_QUOTES);
		$NOMOR_SURAT = htmlentities($this->input->post('NOMOR_SURAT'),ENT_QUOTES);
		$TGL_TERLAMPIR = htmlentities($this->input->post('TGL_TERLAMPIR'),ENT_QUOTES);
		$TGL_PERMOHONAN = htmlentities($this->input->post('TGL_PERMOHONAN'),ENT_QUOTES);
		$STATUS_SURVEY = htmlentities($this->input->post('STATUS_SURVEY'),ENT_QUOTES);
		$STATUS_SURVEY = is_numeric($STATUS_SURVEY) ? $STATUS_SURVEY : 0;
		$STATUS = htmlentities($this->input->post('STATUS'),ENT_QUOTES);
		$STATUS = is_numeric($STATUS) ? $STATUS : 0;
		$TGL_BERLAKU = htmlentities($this->input->post('TGL_BERLAKU'),ENT_QUOTES);
		$TGL_BERAKHIR = htmlentities($this->input->post('TGL_BERAKHIR'),ENT_QUOTES);
				
		$params = array(
			'searchText' => $searchText,
			'NO_SK'=>$NO_SK,
			'NO_SK_LAMA'=>$NO_SK_LAMA,
			'JENIS_PERMOHONAN'=>$JENIS_PERMOHONAN,
			'NAMA_PERUSAHAAN'=>$NAMA_PERUSAHAAN,
			'NPWP'=>$NPWP,
			'ALAMAT'=>$ALAMAT,
			'STATUS_MODAL'=>$STATUS_MODAL,
			'NAMA_NOTARIS'=>$NAMA_NOTARIS,
			'NO_AKTA'=>$NO_AKTA,
			'PENANGGUNG_JAWAB'=>$PENANGGUNG_JAWAB,
			'NAMA_DIREKSI'=>$NAMA_DIREKSI,
			'DEWAN_KOMISARIS'=>$DEWAN_KOMISARIS,
			'TUJUAN_PRODUKSI'=>$TUJUAN_PRODUKSI,
			'LOKASI_PABRIK'=>$LOKASI_PABRIK,
			'LUAS_TANAH'=>$LUAS_TANAH,
			'ALAMAT_PABRIK'=>$ALAMAT_PABRIK,
			'OLAH1_P_TAHUN'=>$OLAH1_P_TAHUN,
			'OLAH1_P_BULAN'=>$OLAH1_P_BULAN,
			'OLAH2_P_TAHUN'=>$OLAH2_P_TAHUN,
			'OLAH2_P_BULAN'=>$OLAH2_P_BULAN,
			'OLAH3_P_TAHUN'=>$OLAH3_P_TAHUN,
			'OLAH3_P_BULAN'=>$OLAH3_P_BULAN,
			'OLAH1_S_TAHUN'=>$OLAH1_S_TAHUN,
			'OLAH1_S_BULAN'=>$OLAH1_S_BULAN,
			'OLAH2_S_TAHUN'=>$OLAH2_S_TAHUN,
			'OLAH2_S_BULAN'=>$OLAH2_S_BULAN,
			'OLAH3_S_TAHUN'=>$OLAH3_S_TAHUN,
			'OLAH3_S_BULAN'=>$OLAH3_S_BULAN,
			'MT_TANAH'=>$MT_TANAH,
			'MT_BANGUNAN'=>$MT_BANGUNAN,
			'MT_MESIN'=>$MT_MESIN,
			'MT_DLL'=>$MT_DLL,
			'MK_BAHAN_BAKU'=>$MK_BAHAN_BAKU,
			'MK_UPAH'=>$MK_UPAH,
			'MK_DLL'=>$MK_DLL,
			'SP_MODAL_SENDIRI'=>$SP_MODAL_SENDIRI,
			'SP_PINJAMAN'=>$SP_PINJAMAN,
			'TKI_L_JUMLAH'=>$TKI_L_JUMLAH,
			'TKI_P_JUMLAH'=>$TKI_P_JUMLAH,
			'TKA_JUMLAH'=>$TKA_JUMLAH,
			'TKA_ASAL'=>$TKA_ASAL,
			'TKA_JABATAN'=>$TKA_JABATAN,
			'TKA_JANGKA_WAKTU'=>$TKA_JANGKA_WAKTU,
			'DN_JENIS_PRODUK1'=>$DN_JENIS_PRODUK1,
			'DN_JENIS_PRODUK2'=>$DN_JENIS_PRODUK2,
			'DN_JENIS_PRODUK3'=>$DN_JENIS_PRODUK3,
			'E_JENIS_PRODUK1'=>$E_JENIS_PRODUK1,
			'E_JENIS_PRODUK2'=>$E_JENIS_PRODUK2,
			'E_JENIS_PRODUK3'=>$E_JENIS_PRODUK3,
			'MERK_JENIS_PRODUK'=>$MERK_JENIS_PRODUK,
			'BBKB_DN_JUMLAH'=>$BBKB_DN_JUMLAH,
			'BBKB_DN_SATUAN'=>$BBKB_DN_SATUAN,
			'BBKB_DN_ASAL'=>$BBKB_DN_ASAL,
			'BBKB_DN_HARGA'=>$BBKB_DN_HARGA,
			'BBKB_DN_KETERANGAN'=>$BBKB_DN_KETERANGAN,
			'BBKO_DN_JUMLAH'=>$BBKO_DN_JUMLAH,
			'BBKO_DN_SATUAN'=>$BBKO_DN_SATUAN,
			'BBKO_DN_ASAL'=>$BBKO_DN_ASAL,
			'BBKO_DN_HARGA'=>$BBKO_DN_HARGA,
			'BBKO_DN_KETERANGAN'=>$BBKO_DN_KETERANGAN,
			'BP_DN_JUMLAH'=>$BP_DN_JUMLAH,
			'BP_DN_SATUAN'=>$BP_DN_SATUAN,
			'BP_DN_ASAL'=>$BP_DN_ASAL,
			'BP_DN_HARGA'=>$BP_DN_HARGA,
			'BP_DN_KETERANGAN'=>$BP_DN_KETERANGAN,
			'RBB_LUAS_GUDANG'=>$RBB_LUAS_GUDANG,
			'RBB_KAYU_OLAHAN'=>$RBB_KAYU_OLAHAN,
			'RBB_PENOLONG'=>$RBB_PENOLONG,
			'RBB_HASIL_PRODUKSI'=>$RBB_HASIL_PRODUKSI,
			'RLPLY_LOKASI'=>$RLPLY_LOKASI,
			'RLPLY_LUAS'=>$RLPLY_LUAS,
			'RLPLY_PERIZINAN'=>$RLPLY_PERIZINAN,
			'RSD1_KAPASITAS'=>$RSD1_KAPASITAS,
			'RSD1_JUMLAH'=>$RSD1_JUMLAH,
			'RSD211_KAPASITAS'=>$RSD211_KAPASITAS,
			'RSD211_JUMLAH'=>$RSD211_JUMLAH,
			'RSD212_KAPASITAS'=>$RSD212_KAPASITAS,
			'RSD212_JUMLAH'=>$RSD212_JUMLAH,
			'RSD213_KAPASITAS'=>$RSD213_KAPASITAS,
			'RSD213_JUMLAH'=>$RSD213_JUMLAH,
			'RSD22_KAPASITAS'=>$RSD22_KAPASITAS,
			'RSD22_JUMLAH'=>$RSD22_JUMLAH,
			'RSD23_KAPASITAS'=>$RSD23_KAPASITAS,
			'RSD23_JUMLAH'=>$RSD23_JUMLAH,
			'RPL1_VOLUME'=>$RPL1_VOLUME,
			'RPL1_SATUAN'=>$RPL1_SATUAN,
			'RPL1_PENANGANAN'=>$RPL1_PENANGANAN,
			'RPL2_VOLUME'=>$RPL2_VOLUME,
			'RPL2_SATUAN'=>$RPL2_SATUAN,
			'RPL2_PENANGANAN'=>$RPL2_PENANGANAN,
			'RPL3_VOLUME'=>$RPL3_VOLUME,
			'RPL3_SATUAN'=>$RPL3_SATUAN,
			'RPL3_PENANGANAN'=>$RPL3_PENANGANAN,
			'RPL4_VOLUME'=>$RPL4_VOLUME,
			'RPL4_SATUAN'=>$RPL4_SATUAN,
			'RPL4_PENANGANAN'=>$RPL4_PENANGANAN,
			'PENYETUJU'=>$PENYETUJU,
			'NOMOR_SURAT'=>$NOMOR_SURAT,
			'TGL_TERLAMPIR'=>$TGL_TERLAMPIR,
			'TGL_PERMOHONAN'=>$TGL_PERMOHONAN,
			'STATUS_SURVEY'=>$STATUS_SURVEY,
			'STATUS'=>$STATUS,
			'TGL_BERLAKU'=>$TGL_BERLAKU,
			'TGL_BERAKHIR'=>$TGL_BERAKHIR,
			'currentAction' => $currentAction,
			'return_type' => 'array',
			'limit_start' => 0,
			'limit_end' => 0
		);
		
		$record = $this->m_iuiphhk->printExcel($params);
		$data['records'] = $record[1];
		$data['type']=$outputType;
		
		$print_view=$this->load->view('template/p_iuiphhk.php',$data,TRUE);
		
		if(!file_exists('print')){ mkdir('print'); }
		if($outputType == 'PRINT'){
			$print_file=fopen('print/iuiphhk_list.html','w+');
		}elseif($outputType == 'EXCEL'){
			$print_file=fopen('print/iuiphhk_list.xls','w+');
		}
		fwrite($print_file, $print_view);
		echo 'success';
	}
	function getRiwayat(){
		$currentAction = $this->input->post('currentAction');
		$id_iuiphhk = $this->input->post('ID_IUIPHHK');
		$result = $this->m_iuiphhk_rencana_alat->get_by(array("ID_IUIPHHK"=>$id_iuiphhk));
		echo $result;
	}
	function getSyarat(){
		$currentAction = $this->input->post('currentAction');
		$iuiphhk_id = $this->input->post('iuiphhk_id');
		// $idam_det_id = $this->input->post('idam_det_id');
		$params = array(
			"currentAction"=>$currentAction,
			"iuiphhk_id"=>$iuiphhk_id
		);
		$result = $this->m_iuiphhk->getSyarat($params);
		echo $result;
	}
	function geiphhk_Alat(){
		$currentAction = $this->input->post('currentAction');
		$iuiphhk_id = $this->input->post('iuiphhk_id');
		// $idam_det_id = $this->input->post('idam_det_id');
		$params = array(
			"currentAction"=>$currentAction,
			"iuiphhk_id"=>$iuiphhk_id
		);
		$result = $this->m_iuiphhk->geiphhk_Alat($params);
		echo $result;
	}
	function printBP(){
		$id_iuiphhk  = $this->input->post('ID_IUIPHHK');
		$this->load->model("m_master_ijin");
		$data["iuiphhk"]	= $this->m_iuiphhk->get_by(array("ID_IUIPHHK"=>$id_iuiphhk),FALSE,FALSE,TRUE);
		$data["ijin"]	= $this->m_master_ijin->get_by(array("ID_IJIN"=>10),FALSE,FALSE,TRUE);
		$print_view		= $this->load->view("template/iuiphhk_bp",$data,true);
		$print_file=fopen('print/iuiphhk_bp.html','w+');
		fwrite($print_file, $print_view);
	}
	function printSK(){
		$id_iuiphhk  = $this->input->post('ID_IUIPHHK');
		$this->load->model("m_master_ijin");
		$join	= array(array("table"=>"iuiphhk","join_key"=>"ID_PEMOHON","join_table"=>"m_pemohon","join_key2"=>"pemohon_id"));
		$data["iuiphhk"]	= $this->m_iuiphhk->get_join_by($join,array("ID_IUIPHHK"=>$id_iuiphhk),TRUE,FALSE);
		$data["ijin"]	= $this->m_master_ijin->get_by(array("ID_IJIN"=>10),FALSE,FALSE,TRUE);
		$print_view		= $this->load->view("template/iuiphhk_sk",$data,true);
		$print_file=fopen('print/iuiphhk_sk.html','w+');
		fwrite($print_file, $print_view);
	}
	function printLK(){
		$ID_IUIPHHK  = $this->input->post('ID_IUIPHHK');
		$join	= array(array("table"=>"iuiphhk","join_key"=>"ID_PEMOHON","join_table"=>"m_pemohon","join_key2"=>"pemohon_id"));
		$printrecord = $this->m_iuiphhk->get_join_by($join,array("ID_IUIPHHK"=>$ID_IUIPHHK),TRUE,FALSE);
		$dataceklist = $this->m_iuiphhk->get_lk($ID_IUIPHHK);
		$data['printrecord'] = $printrecord;
		$data['dataceklist'] = $dataceklist;
		$print_view=$this->load->view('template/iuiphhk_lk',$data,TRUE);
		$print_file=fopen('print/iuiphhk_lk.html','w+');
		fwrite($print_file, $print_view);
	}
	function ubahProses(){
		$iuiphhk_id  = $this->input->post('iuiphhk_id');
		$no_sk  = $this->input->post('no_sk');
		$proses  = $this->input->post('proses');
		($proses == "Selesai, belum diambil") ? ($proses = 2) : (($proses == "Selesai, sudah diambil") ? ($proses = 1) : ($proses = 0));
		if (($no_sk == "" || $no_sk == NULL) && $proses != 0){
			($proses == 2 || $proses == 1) ? ($nosk = $this->m_public_function->getNomorSk("iuiphhk")) : ($nosk = NULL);
			$data = array(
				"NO_SK"=>$nosk,
				"STATUS"=>$proses,
				"TGL_BERLAKU"=>date("Y-m-d")
			);
		} else {
			$data = array(
				"STATUS"=>$proses
			);
		}
		$result = $this->m_iuiphhk->__update($data, $iuiphhk_id, '', '','');
		echo $result;
	}
}