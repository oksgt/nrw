<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Checklog_model extends CI_Model
{

    private $db_212;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->db_212 = $this->load->database('db_212', TRUE);
    }

    public function get_local_last_date_record()
    {
        $qry = 'select max(li.updatedindb) as last_updatedindb from log_inbox li';
        return $this->db->query($qry)->row_array()['last_updatedindb'];
    }

    public function check_compare($last_updatedindb)
    {
        $qry = "
        SELECT 
            UpdatedInDB
        FROM
            inbox 
        WHERE
            UpdatedInDB > '" . $last_updatedindb . "'
            AND UpdatedInDB < now()
        ORDER BY
        UpdatedInDB desc
        ";

        return $this->db_212->query($qry);
    }

    public function get_check_compare($last_updatedindb)
    {
        $qry = "
        SELECT 
            LEFT(TextDecoded,11) as kode,
            SUBSTRING_INDEX( SUBSTRING_INDEX( TextDecoded, 'P', 1 ), 'Q', - 1 ) AS debit ,
            SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'T', 1), 'P', -1) as tekanan,
            left(SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'Z', 1), 'J', -1),2) as jam,
            SUBSTRING_INDEX(SUBSTRING_INDEX(TextDecoded, 'J', 1), 'T', -1) as tgl,
            UpdatedInDB
        FROM
            inbox 
        WHERE
            UpdatedInDB > '" . $last_updatedindb . "'
            AND UpdatedInDB < now()
        ORDER BY
        UpdatedInDB desc
        ";

        return $this->db_212->query($qry);
    }

    public function insert_local($data){
        return $this->db->insert_batch('log_inbox', $data);
    }

    public function cekrowscheme(){
        $sql = "select TABLE_ROWS  from information_schema.TABLES where TABLE_SCHEMA = 'GAMMU_9200' and TABLE_NAME = 'inbox'";
        return $this->db_212->query($sql);
    }
}
