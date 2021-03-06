<?php
class M_ijin_lingkungan extends App_Model{
	var $mainSql = "
				SELECT
				ID_IJIN_LINGKUNGAN,
				ijin_lingkungan.ID_IJIN_LINGKUNGAN_INTI,
				NO_REG,
				NO_SK,
				NAMA_DIREKTUR,
				JENIS_PERMOHONAN,
				TGL_PERMOHONAN,
				TGL_AWAL,
				TGL_AKHIR,
				STATUS,
				STATUS_SURVEY,
				NPWPD,
				NAMA_PERUSAHAAN,
				NO_AKTE,
				BENTUK_PERUSAHAAN,
				ALAMAT_PERUSAHAAN,
				ID_KOTA,
				ID_KECAMATAN,
				ID_KELURAHAN,
				NAMA_KEGIATAN,
				JENIS_USAHA,
				ALAMAT_LOKASI,
				ID_KELURAHAN_LOKASI,
				ID_KECAMATAN_LOKASI,
				STATUS_LOKASI,
				LUAS_USAHA,
				LUAS_BAHAN,
				LUAS_BANGUNAN,
				LUAS_RUANG_USAHA,
				KAPASITAS,
				IZIN_SKTR,
				IZIN_LOKASI,
				pemohon.id AS pemohon_id,
				pemohon.nama AS pemohon_nama,
				pemohon.alamat AS pemohon_alamat,
				pemohon.telp AS pemohon_telp,
				pemohon.npwp AS pemohon_npwp,
				pemohon.rt AS pemohon_rt,
				pemohon.rw AS pemohon_rw,
				pemohon.desa_id AS pemohon_kel,
				pemohon.kecamatan_id AS pemohon_kec,
				pemohon.ktp AS pemohon_nik,
				pemohon.stra AS pemohon_stra,
				pemohon.surattugas AS pemohon_surattugas,
				pemohon.pekerjaan AS pemohon_pekerjaan,
				pemohon.tempatlahir AS pemohon_tempatlahir,
				pemohon.tgllahir AS pemohon_tanggallahir,
				pemohon.pendidikan AS pemohon_pendidikan,
				pemohon.tahunlulus AS pemohon_tahunlulus,
				perusahaan.id AS perusahaan_id,
				perusahaan.npwp AS perusahaan_npwp,
				perusahaan.nama AS perusahaan_nama,
				perusahaan.noakta AS perusahaan_noakta,
				perusahaan.notaris AS perusahaan_notaris,
				perusahaan.tglakta AS perusahaan_tglakta,
				perusahaan.bentuk_id AS perusahaan_bentuk_id,
				perusahaan.kualifikasi_id AS perusahaan_kualifikasi_id,
				perusahaan.alamat AS perusahaan_alamat,
				perusahaan.rt AS perusahaan_rt,
				perusahaan.rw AS perusahaan_rw,
				perusahaan.propinsi_id AS perusahaan_propinsi_id,
				perusahaan.kabkota_id AS perusahaan_kabkota_id,
				perusahaan.kecamatan_id AS perusahaan_kecamatan_id,
				perusahaan.desa_id AS perusahaan_desa_id,
				perusahaan.kodepos AS perusahaan_kodepos,
				perusahaan.telp AS perusahaan_telp,
				perusahaan.fax AS perusahaan_fax,
				perusahaan.stempat_id AS perusahaan_stempat_id,
				perusahaan.sperusahaan_id AS perusahaan_sperusahaan_id,
				perusahaan.usaha AS perusahaan_usaha,
				perusahaan.butara AS perusahaan_butara,
				perusahaan.bselatan AS perusahaan_bselatan,
				perusahaan.btimur AS perusahaan_btimur,
				perusahaan.bbarat AS perusahaan_bbarat,
				perusahaan.modal AS perusahaan_modal,
				perusahaan.merk AS perusahaan_merk,
				perusahaan.jusaha_id AS perusahaan_jusaha_id
				FROM ijin_lingkungan
				JOIN ijin_lingkungan_inti ON ijin_lingkungan_inti.ID_IJIN_LINGKUNGAN_INTI = ijin_lingkungan.ID_IJIN_LINGKUNGAN_INTI
				JOIN pemohon ON ijin_lingkungan_inti.ID_PEMOHON = pemohon.id
				JOIN perusahaan ON ijin_lingkungan_inti.ID_PERUSAHAAN = perusahaan.id
			WHERE ID_IJIN_LINGKUNGAN IS NOT NULL 
	";
	
	function __construct(){
        parent::__construct();
        $this->table_name = 'ijin_lingkungan';
        $this->column_primary = 'ID_IJIN_LINGKUNGAN';
        $this->column_order = 'ID_IJIN_LINGKUNGAN ASC';
		$this->column_unique = '';
    }
	
	function getList($params){
		extract($params);
		$sql = $this->mainSql;
		if(@$searchText != ''){
			$sql .= "
				AND (
					ID_IJIN_LINGKUNGAN_INTI LIKE '%".$searchText."%' OR 
					NO_REG LIKE '%".$searchText."%' OR 
					NO_SK LIKE '%".$searchText."%' OR 
					NAMA_DIREKTUR LIKE '%".$searchText."%' OR 
					JENIS_PERMOHONAN LIKE '%".$searchText."%' OR 
					TGL_PERMOHONAN LIKE '%".$searchText."%' OR 
					TGL_AWAL LIKE '%".$searchText."%' OR 
					TGL_AKHIR LIKE '%".$searchText."%' OR 
					STATUS LIKE '%".$searchText."%' OR 
					STATUS_SURVEY LIKE '%".$searchText."%'
					)
			";
		}
				if(@$limit_start != 0 && @$limit_start != 0){
			$sql .= " LIMIT ".@$limit_start.", ".@$limit_end." ";
		}
		$result = $this->__listCore($sql, $params);
		return $result;
	}
	
	function search($params){
		extract($params);
		
		$sql = $this->mainSql;
		
		if(@$ID_IJIN_LINGKUNGAN_INTI != ''){
			$sql .= " AND ID_IJIN_LINGKUNGAN_INTI LIKE '%".$ID_IJIN_LINGKUNGAN_INTI."%' ";
		}
		if(@$NO_REG != ''){
			$sql .= " AND NO_REG LIKE '%".$NO_REG."%' ";
		}
		if(@$NO_SK != ''){
			$sql .= " AND NO_SK LIKE '%".$NO_SK."%' ";
		}
		if(@$NAMA_DIREKTUR != ''){
			$sql .= " AND NAMA_DIREKTUR LIKE '%".$NAMA_DIREKTUR."%' ";
		}
		if(@$JENIS_PERMOHONAN != ''){
			$sql .= " AND JENIS_PERMOHONAN LIKE '%".$JENIS_PERMOHONAN."%' ";
		}
		if(@$TGL_PERMOHONAN != ''){
			$sql .= " AND TGL_PERMOHONAN LIKE '%".$TGL_PERMOHONAN."%' ";
		}
		if(@$TGL_AWAL != ''){
			$sql .= " AND TGL_AWAL LIKE '%".$TGL_AWAL."%' ";
		}
		if(@$TGL_AKHIR != ''){
			$sql .= " AND TGL_AKHIR LIKE '%".$TGL_AKHIR."%' ";
		}
		if(@$STATUS != ''){
			$sql .= " AND STATUS LIKE '%".$STATUS."%' ";
		}
		if(@$STATUS_SURVEY != ''){
			$sql .= " AND STATUS_SURVEY LIKE '%".$STATUS_SURVEY."%' ";
		}
		if($_SESSION["IDHAK"] == 2){
			$sql .= " AND m_pemohon.pemohon_user_id = " . $_SESSION["USERID"];
		}
		if(@$limit_start != 0 && @$limit_start != 0){
			$sql .= " LIMIT ".@$limit_start.", ".@$limit_end." ";
		}
		$result = $this->__listCore($sql, $params);
		return $result;
	}
	
	function printExcel($params){
		extract($params);
		if(@$currentAction == "GETLIST"){
			$result = $this->getList($params);
		}else if(@$currentAction == "SEARCH"){
			$result = $this->search($params);
		}
		return $result;
	}
	function getSyarat($params){
		extract($params);
		if($currentAction == 'update'){
			$sql = "
				SELECT cek_list_lingkungan.ID_SYARAT,cek_list_lingkungan.ID_IJIN,cek_list_lingkungan.`STATUS`,cek_list_lingkungan.KETERANGAN,master_syarat.NAMA_SYARAT FROM cek_list_lingkungan RIGHT JOIN dt_syarat ON dt_syarat.ID_SYARAT = cek_list_lingkungan.ID_SYARAT AND cek_list_lingkungan.ID_IJIN = '" . $lingkungan_id . "' JOIN master_syarat ON master_syarat.ID_SYARAT = dt_syarat.ID_SYARAT WHERE dt_syarat.ID_IJIN = 8;
			";
		}else{
			$sql = "
				SELECT master_syarat.ID_SYARAT,master_syarat.NAMA_SYARAT FROM `dt_syarat` JOIN master_syarat ON master_syarat.ID_SYARAT=dt_syarat.ID_SYARAT WHERE dt_syarat.ID_IJIN = 8;
			";
		}
		$result = $this->__listCore($sql, $params);
		return $result;
	}
	function getSyarat2(){
		$query = $this->db->query("SELECT master_syarat.ID_SYARAT,master_syarat.NAMA_SYARAT FROM `dt_syarat` JOIN master_syarat ON master_syarat.ID_SYARAT=dt_syarat.ID_SYARAT WHERE dt_syarat.ID_IJIN = 8;");
		return $query->result_array();
	}
	function get_lk($lingkungan_id){
		$query	= $this->db->query("SELECT cek_list_lingkungan.ID_SYARAT,cek_list_lingkungan.ID_IJIN,cek_list_lingkungan.`STATUS`,cek_list_lingkungan.KETERANGAN,master_syarat.NAMA_SYARAT FROM cek_list_lingkungan RIGHT JOIN dt_syarat ON dt_syarat.ID_SYARAT = cek_list_lingkungan.ID_SYARAT AND cek_list_lingkungan.ID_IJIN = '" . $lingkungan_id . "' JOIN master_syarat ON master_syarat.ID_SYARAT = dt_syarat.ID_SYARAT WHERE dt_syarat.ID_IJIN = 8");
		return $query;
	}
}