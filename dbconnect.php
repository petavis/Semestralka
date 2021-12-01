<?php

class dbConnection {
    private $db;
    private const DB_HOST = 'localhost';
    private const DB_NAME = 'poster';
    private const DB_USER = 'root';
    private const DB_PASS = 'dtb456';

    public function __construct()
    {
        $this->db = mysqli_connect(self::DB_HOST, self::DB_USER, self::DB_PASS, self::DB_NAME);
        $this->db == false ? die('Error' . mysqli_connect_error()) : '';
    }

    function verify($meno = '', $priezvisko = '', $email='', $pohlavie='', $kurz = '') {
        IF ($email) {
            $email_trim = trim($email);
            if($email_trim == '') {
                return false;
            }

            if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
                return false;
            }
        }

        IF ($meno) {
            $meno_trim = trim($meno);
            if($meno_trim == '') {
                return false;
            }

            if (!preg_match("/[a-zA-Z\s]/i", $meno)) {
                return false;
            }
        }

        IF ( $priezvisko ) {
            $priez_trim = trim($priezvisko);
            if($priez_trim == '') {
                return false;
            }

            if (!preg_match("/[a-zA-Z\s]/i", $priezvisko)) {
                return false;
            }
        }

        IF ($pohlavie) {
            $poh_trim = trim($pohlavie);
            if($poh_trim == '') {
                return false;
            }

            if (!preg_match("/[a-zA-Z\s]/i", $pohlavie)) {
                return false;
            }
        }

        IF ($kurz) {
            $kurz_trim = trim($kurz);
            if($kurz_trim == '') {
                return false;
            }
            if (!preg_match("/[a-zA-Z\s-]/i", $kurz)) {
                return false;
            }
        }

        return true;
    }

    function vytvorOsobu($p_meno, $p_priezvisko, $p_email, $p_pohlavie)
    {
        $meno = mysqli_real_escape_string($this->db, $p_meno);
        $priezvisko = mysqli_real_escape_string($this->db, $p_priezvisko);
        $email = mysqli_escape_string($this->db, $p_email);
        $pohlavie = mysqli_escape_string($this->db, $p_pohlavie);

        IF ($this->verify(meno: $meno, priezvisko: $priezvisko, email: $email, pohlavie: $pohlavie )) {
            $sql = "INSERT INTO osoba(meno, priezvisko, email, pohlavie) VALUES ('$meno', '$priezvisko', '$email', '$pohlavie')";

            if (mysqli_query($this->db, $sql)) {
            } else {
                echo 'ERROR: $sql' . mysqli_error($this->db);
            }
        }
    }

    function vytvorZaznam($email, $kurz) {
        $sql = "SELECT * FROM prihlasenia WHERE email_prih = '$email' AND meno_kurz = '$kurz'";
        $result = mysqli_query($this->db, $sql);

        if ($result) {
          if ($result->num_rows === 0) {
                $sql = "INSERT INTO prihlasenia ( email_prih, meno_kurz) VALUES ( '$email', '$kurz')";
                if (mysqli_query($this->db, $sql)) {
                } else {
                    echo 'ERROR: $sql' . mysqli_error($this->db);
                }
            } else {
              return false;
          }
        }
    }

    function zmenUdaje($p_email, $p_meno, $p_priezvisko, $p_pohlavie) {

        $meno = mysqli_escape_string($this->db, $p_meno);
        $priezvisko = mysqli_real_escape_string($this->db, $p_priezvisko);
        $email = mysqli_escape_string($this->db, $p_email);
        $pohlavie = mysqli_escape_string($this->db, $p_pohlavie);

        if ($this->verify(meno: $meno, priezvisko: $priezvisko, email: $email,  pohlavie: $pohlavie) === false) {
            return false;
        }

        $sql = "UPDATE osoba SET ";
        if ($p_meno) {
            $sql = $sql . "meno = '$meno', ";
        }

        if ($p_priezvisko) {
            $sql = $sql . "priezvisko = '$priezvisko', ";
        }

        if ($p_pohlavie) {
            $sql = $sql . "pohlavie = '$pohlavie' ";
        }

        $sql = $sql . "WHERE email = '$email';";
        $result = mysqli_query($this->db, $sql);

        if ($result) {
            if ($result->num_rows === 0) {
                return false;
            }
        } else {
            return false;
        }
    }

    function odhlasZkurzu($p_email, $p_kurz) {
        $email = mysqli_escape_string($this->db, $p_email);
        $kurz = mysqli_escape_string($this->db, $p_kurz);

        if ($this->verify(email: $email, kurz: $kurz) === false) {
            return false;
        }

        $sql = "DELETE FROM prihlasenia WHERE email_prih = '$email' AND meno_kurz = '$kurz'";
        $result = mysqli_query($this->db, $sql);

        if($result) {

        }
    }

    function registracia($p_meno, $p_priezvisko, $p_email, $p_pohlavie, $p_kurz) {
        $email = mysqli_real_escape_string($this->db, $p_email);
        $kurz = mysqli_real_escape_string($this->db, $p_kurz);
        $meno = mysqli_real_escape_string($this->db, $p_meno);
        $priezvisko = mysqli_escape_string($this->db, $p_priezvisko);
        $pohlavie = mysqli_escape_string($this->db, $p_pohlavie);

        if ( $this->verify(meno: $meno, priezvisko: $priezvisko, email: $email, pohlavie: $pohlavie, kurz: $kurz) === false ) {
            return false;
        }

        $sql = "SELECT email FROM osoba WHERE email = '$email'";

        $result = mysqli_query($this->db, $sql);


        if($result) {
            if ($result->num_rows === 0) {
                $this->vytvorOsobu($meno, $priezvisko, $email, $pohlavie);
            }
        }

        return $this->vytvorZaznam($email, $kurz);
    }

    function nacitajKurzyZDB() {
        $sql = 'SELECT * FROM KURZ';
        $result = mysqli_query($this->db, $sql);
        return $result;
    }
}
?>