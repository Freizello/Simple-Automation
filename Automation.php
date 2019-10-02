<?php

/* 
CARA PENGGUNAAN :

1.  define class Automation ke dalam sebuah var dengan
    memberikan parameter host, username, password.
    *cth : $var = new Automation('localhost', 'username', 'password');

2.  generate nama file dengan dengan fungsi GenerateFilename() beserta
    parameter nya berupa prefix, formatfile, dan bulan jika ada.
    
    Jika bulan tidak di set, maka dianggap file yang diambil adalah tanggal hari ini dikurang 2 hari (untuk mengubah pengurangan hari, edit "strtotime('-2 days') di fungsi GenerateFilename()".
    
    Dengan bulan
    *cth : $var->generateFilename('report_physicalvoucher_', '.csv', 01)
    
    Tanpa bulan
    *cth : $var->generateFilename('report_physicalvoucher_', '.csv')

3.  ambil file dengan fungsi get_file() dan berikan parameter
    localdir_filename dan ftpdir_filename sebagai tujuan file yang
    akan diambil.

    format : get_file('direktori_local+filename+extension', 'direktory_ftp+filename+extension');

    *cth : $auto->get_file('D:\SAMPLE_TEST_FILE.txt', '\folder\di\ftp\server\SAMPLE_TEST_FILE.txt');
*/

class Automation {
    public $host, $user, $pass, $conn;

    public function __construct(string $host, string $user, string $pass) {
        $this->host = $host;
        $this->pass = $user;
        $this->pass = $pass;

        $this->conn = ftp_connect($host) or die("Tidak dapat konek ke server $host");

        ftp_login($this->conn, $user, $pass);
    }
    
    public function GenerateFilename(string $prefix, string $ext, int $bulan=NULL)
    {

        if ($bulan < 10) {
            $bulan = strval('0'.$bulan);
        } 
        
        $filename = '';
        if ($bulan == NULL || $bulan == date('m')) {
            $filedate = date('Ymd', strtotime('-2 days'));
            $filename = $prefix.$filedate.$ext;
        } else {
            if ($bulan < date('m')) {
                // print_r('Bulan diset ke bulan sebelum ' . date('F'));
                $setdate = date('Ymt', strtotime('2019'.$bulan.'01'));
                $filename = $prefix.$setdate.$ext;
            } elseif ($bulan > date('m')) {
                print_r('ERROR :: Bulan di setting bulan depan, set bulan maksimal bulan '. date('F'));
                exit;
            } else {
                print_r('ERROR :: Ada kesalahan set bulan.');
                exit;
            }
        };
    return $filename;
    }

    public function close_ftp()
    {
        $host = $this->host;
        $conn = $this->conn;

        ftp_close($conn);
    }

    public function get_file(string $localPath, string $ftpPath)
    {
        $conn = $this->conn;
        if(ftp_get($conn, $localPath, $ftpPath, FTP_BINARY)){
            print_r("File transfer successful - $localPath \n");
        } else {
            print_r("There was an error while downloading $localPath \n");
        }
    }

    public function get_bulkFiles(string $prefix, string $start, string $end, string $ext, string $local, string $ftpdir)
    {
        // TODO :
        // lakukan perulangan untuk menggenerate filename dengan tanggal
        // dalam perulangan tsb, jalankan fungsi get_file()

        $startdate = strtotime($start);
        $enddate = strtotime($end);
        
        for ($i=$startdate; $i<=$enddate; $i+=86400) {  
            $localfilename = $local.$prefix.date('Ymd',$i).$ext;
            $ftpfilename = $ftpdir.$prefix.date('Ymd', $i).$ext;
            
            $this->get_file($localfilename, $ftpfilename);
        };
    }

}

$var = new Automation('127.0.0.1', 'test', 'test');
$var->get_bulkFiles('prefix_', '20190901', '20190907', '.txt', 'D:\\', 'folder_test/')

?>
