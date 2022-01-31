<?php
function rupiah($angka){
    $hasil_rupiah = "" . number_format($angka,0,'.','.');
    return $hasil_rupiah;
  }
  
  function formatTglIndo($stringDate){
    $pieces = explode("-", $stringDate);
    $bulan = $pieces[1];
    $namaBulan = bulan($bulan);
    return $pieces[2].' '.$namaBulan.' '.$pieces[0];
  }
  
  function formatTglIndo_3($stringDate){
    $pieces = explode("-", $stringDate);
    $bulan = $pieces[1];
    $namaBulan = bulan_1($bulan);
    return $pieces[2].' '.$namaBulan.' '.$pieces[0];
  }
  
  function formatTglIndo_2($stringDate){
    if($stringDate!='' || $stringDate!=null){
      if ($stringDate == "0000-00-00") {
        return " ";
      }else{
        $pieces = explode("-", $stringDate);
        $bulan = $pieces[1];
        return $pieces[2].'/'.$bulan.'/'.$pieces[0];
      }
    }else {
      return " ";
    }
  }
  
  function formatTglIndo_datetime($stringDate){
    $pieces_raw = explode(" ", $stringDate);
    $pieces = explode("-", $pieces_raw[0]);
    $bulan = $pieces[1];
    return $pieces[2].' '.bulan_1($bulan).' '.$pieces[0] . ' - ' .$pieces_raw[1];
  }
  
  function formatTglIndo_datetime_2($stringDate){
    $pieces_raw = explode(" - ", $stringDate);
    $pieces = explode(" ", $pieces_raw[0]);
    $bulan = $pieces[1];
    return $pieces[0].' '.$pieces[1] . ' ' . $pieces[2];
  }
  
  function formatTglIndo_datetime_3($stringDate){
    $pieces_raw = explode(" ", $stringDate);
    $pieces = explode("-", $pieces_raw[0]);
    $bulan = $pieces[1];
  
    $time_raw = explode(":", $pieces_raw[1]);
  
    return $pieces[2].' '.bulan($bulan).' '.$pieces[0] . ' - ' .$time_raw[0].':'.$time_raw[1];
  }

  function format_month_year($stringDate){
    if($stringDate!== null){
      $pieces_raw = explode("-", $stringDate);
      return bulan_1($pieces_raw[0]) . " " . $pieces_raw[1];
    } else {
      return "-";
    }
  }
  
  function hari($day){
    switch ($day) {
      case 'Sunday': return 'Minggu';
      break;
      case 'Monday': return 'Senin';
      break;
      case 'Tuesday': return 'Selasa';
      break;
      case 'Wednesday': return 'Rabu';
      break;
      case 'Thursday': return 'Kamis';
      break;
      case 'Friday': return "Jumat";
      break;
      case 'Saturday': return 'Sabtu';
      break;
  
    }
  }
  
  function bulan_1($bulan){
  
    switch ($bulan) {
      case '01': return 'Januari';
      break;
  
      case '02': return 'Februari';
      break;
  
      case '03': return 'Maret';
      break;
  
      case '04': return 'April';
      break;
  
      case '05': return 'Mei';
      break;
  
      case '06': return 'Juni';
      break;
  
      case '07': return 'Juli';
      break;
  
      case '08': return 'Agustus';
      break;
  
      case '09': return 'September';
      break;
  
      case '10': return 'Oktober';
      break;
  
      case '11': return 'November';
      break;
  
      case '12': return 'Desember';
      break;
    }
  
  }
  
  function bulan($bulan){
  
    switch ($bulan) {
      case '01': return 'Jan';
      break;
  
      case '02': return 'Feb';
      break;
  
      case '03': return 'Mar';
      break;
  
      case '04': return 'Apr';
      break;
  
      case '05': return 'Mei';
      break;
  
      case '06': return 'Jun';
      break;
  
      case '07': return 'Jul';
      break;
  
      case '08': return 'Aug';
      break;
  
      case '09': return 'Sept';
      break;
  
      case '10': return 'Okt';
      break;
  
      case '11': return 'Nov';
      break;
  
      case '12': return 'Des';
      break;
    }
  

    
  }

  function conver_ldt_m3($value){
    $res = $value/1000*(86400*24*30);
    return rupiah($res)." &#13221;";
  }
  