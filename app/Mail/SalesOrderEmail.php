<?php
    namespace App\Mail;
    use Illuminate\Bus\Queueable;
    use Illuminate\Mail\Mailable;
    use Illuminate\Queue\SerializesModels;
    use Illuminate\Contracts\Queue\ShouldQueue;
    
    class SalesOrderEmail extends Mailable
    {
        use Queueable, SerializesModels;
    
        public $param1;
    
        public function __construct($param1)
        {
            $this->param1 = $param1; 
        }
        public function build()
        {
            return $this->view('SalesOrderConfirmationEmail')
                        ->subject('Sales Order Email');
        }
    }
?>