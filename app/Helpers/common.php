<?php

use App\Models\UserManagement;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

function getSideBar1()
{

    // <li>
    // <a href="/MDDashboard1" class="waves-effect">
    // <i class="bx bx-stats"></i>
    // <span key="t-dashboards">ERP Dashboard</span>
    // </a>
    // </li>

    //   <li>
    // <a href="/dashboard2nd">
    // <i class="bx bx-home-circle"></i>
    // <span key="t-dashboards">Dashboard</span>
    // </a>

    // </li> 


    $html = '';

    $html .= '<ul class="metismenu list-unstyled" id="side-menu">
    <li class="menu-title" key="t-menu">Menu</li>
   

    <li>
            <a href="javascript: void(0);" class="has-arrow waves-effect head11" onclick="GetSubSideMenu(11);">
                <i class="bx bx-home-circle"></i>
                <span key="t-dashboards">Dashboard</span>
            </a>
    </li>
      

    <li>
    <a href="javascript: void(0);" class="has-arrow waves-effect head1" onclick="GetSubSideMenu(1);">
    <i class="bx bx-book"></i>
    <span key="t-ecommerce">Masters</span>
    </a> 
    </li> 
    <li>
    <a href="javascript: void(0);" class="has-arrow waves-effect head2" onclick="GetSubSideMenu(2);">
    <i class="bx bx-calendar-event"></i>
    <span key="t-ecommerce">Transaction</span>
    </a> 
    </li>
    
    <li class="hide">
    <a href="javascript: void(0);" class="has-arrow waves-effect head2" onclick="GetSubSideMenu(13);">
    <i class="bx bx-calendar-event"></i>
    <span key="t-ecommerce">CRM</span>
    </a> 
    </li>

    <li>
    <a href="javascript: void(0);" class="has-arrow waves-effect head7" onclick="GetSubSideMenu(7);">
    <i class="bx bx-archive-in"></i>
    <span key="t-ecommerce">Samples</span>
    </a> 
    </li>

    <li>
    <a href="javascript: void(0);" class="has-arrow waves-effect head7" onclick="GetSubSideMenu(12);">
    <i class="bx bx-archive-in"></i>
    <span key="t-ecommerce">Pcs Rate Prod.</span>
    </a> 
    </li>
    
    <li class="hide">
    <a href="javascript: void(0);" class="has-arrow waves-effect head8" onclick="GetSubSideMenu(8);">
    <i class="bx bx-reply"></i>
    <span key="t-ecommerce">Outlet</span>
    </a> 
    </li>
    <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect head9" onclick="GetSubSideMenu(9);">
        <i class="bx bx-file"></i>
        <span key="t-ecommerce">Finishing Bill</span>
        </a> 
    </li>';

    $html .= '<li>
        <a href="javascript: void(0);" class="has-arrow waves-effect head10" onclick="GetSubSideMenu(10);">
        <i class="bx bxs-stopwatch"></i>
        <span key="t-ecommerce">Maintenance</span>
        </a> 
    </li>
    
    <li>
        <a href="/ReportViewerDashboard" class="has-arrow waves-effect head5">
        <i class="bx bx-file"></i>
        <span key="t-ecommerce">Reports</span>
        </a> 
    </li>
     <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect head14" onclick="GetSubSideMenu(14);">
        <i class="bx bx-file"></i>
        <span key="t-ecommerce">Operation</span>
        </a> 
    </li>
     <li>
        <a href="javascript: void(0);" class="has-arrow waves-effect head15" onclick="GetSubSideMenu(15);">
        <i class="bx bx-file"></i>
        <span key="t-ecommerce">Outlet</span>
        </a> 
    </li>
    
    ';

    $html .= "</ul>";

    echo $html;
}

function getSideBar()
{

    //  $Authicateuser = UserManagement::join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
    //     ->where('form_auth.emp_id', '=', Session::get('userId'))
    //     ->where('form_master.delflag', 0)
    //     ->groupBy('form_master.form_code')
    //     ->orderBy('form_master.seq_no', 'ASC')
    //     ->get([
    //         'form_master.form_code',
    //         'form_master.form_label',
    //         'form_master.form_name',
    //         'form_auth.write_access',
    //         'form_auth.edit_access',
    //         'form_auth.delete_access',
    //         'form_master.head_id',
    //     ]);


    $Authicateuser = UserManagement::from('form_master')
        ->join('form_auth', 'form_auth.form_id', '=', 'form_master.form_code')
        ->where('form_auth.emp_id', Session::get('userId'))
        ->where('form_master.delflag', 0)
        ->groupBy('form_master.form_code', 'form_master.seq_no', 'form_master.form_label', 'form_master.form_name', 'form_auth.write_access', 'form_auth.edit_access', 'form_auth.delete_access', 'form_master.head_id')
        ->orderBy('form_master.head_id', 'ASC')
        ->orderBy('form_master.seq_no', 'ASC')
        ->get([
            'form_master.form_code',
            'form_master.form_label',
            'form_master.form_name',
            'form_auth.write_access',
            'form_auth.edit_access',
            'form_auth.delete_access',
            'form_master.head_id',
        ]);


    $html = '<ul class="metismenu list-unstyled" id="side-menu">';

    // Generate menu items based on head_id
    $head_ids = [1, 2, 3, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15];
    foreach ($head_ids as $head_id) {
        $html .= '<li class="suhead head' . $head_id . '">
            <a href="javascript: void(0);" class="has-arrow waves-effect"></a>
            <ul class="sub-menu" aria-expanded="false">';

        foreach ($Authicateuser as $check) {
            if ($check->head_id == $head_id) {
                $html .= '<li>
                    <a href="' . route($check->form_name) . '" key="t-customers">' .
                    $check->form_label .
                    '</a>
                </li>';
            }
        }

        $html .= '</ul></li>';
    }

    $html .= '</ul>';

    echo $html;
}


function indian_number_format_wd($num)
{
    // Check if the number is negative
    $is_negative = false;
    if (strpos($num, '-') === 0) {
        $is_negative = true;
        $num = substr($num, 1); // Remove the minus sign temporarily
    }

    $num = explode('.', $num);
    $dec = (count($num) == 2) ? '.' . $num[1] : ''; // Default decimal part to .00 if not provided
    $num = (string)$num[0];

    if (strlen($num) < 4) {
        // If less than 1000, pad the number with zeros to ensure two decimal places
        if (strlen($num) === 1) {
            $num = '0' . $num; // Add leading zero if only one digit
        }
        return ($is_negative ? '-' : '') . $num . $dec;
    }

    $tail = substr($num, -3);
    $head = substr($num, 0, -3);
    $head = preg_replace("/\B(?=(?:\d{2})+(?!\d))/", ",", $head);

    return ($is_negative ? '-' : '') . $head . "," . $tail;
}
/*
function indian_number_format_for_value($num, $decimals = 2)
{
    $num = number_format($num, $decimals, '.', ''); // normalize

    $parts = explode('.', $num);
    $integer = $parts[0];
    $decimal = isset($parts[1]) ? $parts[1] : '';

    $last3 = substr($integer, -3);
    $rest = substr($integer, 0, -3);

    if ($rest != '') {
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
        return $rest . "," . $last3 . ($decimal ? "." . $decimal : "");
    }
    return $integer . ($decimal ? "." . $decimal : "");
}*/

function indian_number_format_for_value($num, $decimals = 2)
{
    // Check for negative number
    $isNegative = $num < 0;

    // Work with absolute value for formatting
    $num = abs($num);

    // Normalize to fixed decimals
    $num = number_format($num, $decimals, '.', '');

    $parts = explode('.', $num);
    $integer = $parts[0];
    $decimal = isset($parts[1]) ? $parts[1] : '';

    $last3 = substr($integer, -3);
    $rest = substr($integer, 0, -3);

    if ($rest != '') {
        $rest = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $rest);
        $formatted = $rest . "," . $last3;
    } else {
        $formatted = $integer;
    }

    // Add decimal part back
    if ($decimal !== '') {
        $formatted .= "." . $decimal;
    }

    // Re-attach negative sign if original number was negative
    return $isNegative ? "-" . $formatted : $formatted;
}


function setEnvironmentValue($key, $value)
{
    $path = base_path('.env');

    if (file_exists($path)) {
        // Read the content of the .env file
        $env = file_get_contents($path);

        // Replace the existing value with the new one
        $keyValue = $key . '="' . $value . '"';
        if (strpos($env, $key . '=') !== false) {
            $env = preg_replace('/^' . $key . '=.*/m', $keyValue, $env);
        } else {
            // Append the key if it doesn't exist
            $env .= "\n" . $keyValue;
        }

        // Write the updated content back to the .env file
        file_put_contents($path, $env);
    }
}

function getCompanyInfo()
{
    return [
        'name'    => 'Ken Global Designs Pvt Ltd',
        'address' => 'Gat No.- 298/299, A/P Kondigre Kolhapur Maharashtra - 416101',
        'pan'     => 'ABCCS7591Q',
        'gst'     => '27ABCCS7591Q1ZD',
        'state'   => 'MAHARASHTRA'
    ];
}

function getCompanyAddress()
{
    return [

        'heading' => 'KEN GLOBAL DESIGNS PRIVATE LIMITED',

        'address' => 'Reg.Office:18/20 Back Side Of Hotel City In, Industrial Estate, Ichalkaranji-416115<br>
                     Tal Hatkanangale Dist Kolhapur Maharashtra INDIA.<br>
                     Works: Gat No 298&299,At Kondigare, Tal: Shirol, Dist: Kolhapur-416102 <br>
                     Tel : +91230 2438538 Email:office@kenindia.in'        
    ];
}





// function convertInvoice($number) 
// {
//         $hyphen      = '-';
//         $conjunction = ' and ';
//         $separator   = ', ';
//         $negative    = 'negative ';
//         $dictionary  = [
//             0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
//             5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
//             10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
//             14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
//             18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
//             40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
//             80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
//             100000 => 'Lakh', 10000000 => 'Crore'
//         ];

//         if (!is_numeric($number)) return false;
//         if ($number < 0) return $negative . self::convert(abs($number));

//         $string = '';

//         if ($number < 21) {
//             $string = $dictionary[$number];
//         } elseif ($number < 100) {
//             $tens   = ((int) ($number / 10)) * 10;
//             $units  = $number % 10;
//             $string = $dictionary[$tens];
//             if ($units) {
//                 $string .= $hyphen . $dictionary[$units];
//             }
//         } elseif ($number < 1000) {
//             $hundreds  = (int) ($number / 100);
//             $remainder = $number % 100;
//             $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
//             if ($remainder) {
//                 $string .= $conjunction . self::convert($remainder);
//             }
//         } else {
//             foreach ([10000000 => 'Crore', 100000 => 'Lakh', 1000 => 'Thousand', 100 => 'Hundred'] as $value => $name) {
//                 if ($number >= $value) {
//                     $count = floor($number / $value);
//                     $number %= $value;
//                     $string .= self::convert($count) . ' ' . $name;
//                     if ($number) $string .= $separator;
//                 }
//             }
//             if ($number) $string .= self::convert($number);
//         }

//         return $string;

// }

if (!function_exists('money_format')) {
    function money_format($format, $number)
    {
        return number_format($number, 2, '.', ',');
    }
}
