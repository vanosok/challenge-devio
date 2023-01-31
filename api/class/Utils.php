<?php

class Utils
{
    public function validationTextType($text, $type)
    {

        //Tipo String
        if ($type === 'string') {
            if (is_string($text)) :
                return true;
            else :
                return false;
            endif;
        }
        //Tipo Integer
        if ($type === 'integer') {
            if (is_numeric($text)) :
                return true;
            else :
                return false;
            endif;
        }
        //Formato de E-mail
        if ($type === 'email') {
            if (filter_var($text, FILTER_VALIDATE_EMAIL)) :
                return true;
            else :
                return false;
            endif;
        }
        if ($type === 'date') {
            $date_array = explode('/', $text);

            if (
                strtotime(implode('-', array_reverse(explode('/', $text)))) < strtotime(date('Y-m-d')) &&
                count($date_array) == 3 && checkdate($date_array[1], $date_array[0], $date_array[2])
            ) {
                return true;
            }
            return false;
        }
        //Validar o CEP
        if ($type === 'cep') {
            if (preg_match('/[0-9]{5,5}([-]?[0-9]{3})?$/', $text)) :
                return true;
            else :
                return false;
            endif;
        }
        //Validar número de Telefone (99) 99999-9999 ou (99) 9999-9999
        if ($type === 'phone') {
            if (preg_match('/^\([0-9]{2}\)?\s?[0-9]{4,5}-[0-9]{4}$/', $text)) :
                return true;
            else :
                return false;
            endif;
        }
    }
 
    function createPDF($order_data, $name_printer = null)
    {
        require 'C:\Users\PC-GAMER\Documents\GitHub\challenge-devio\api\lib\fdpf\fpdf.php';

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        // Adicionando informações do pedido
        $pdf->Cell(40, 10, 'Numero do pedido: ' . $order_data['numero']);
        $pdf->Ln();
        $pdf->Cell(40, 10, 'Cliente: ' . $order_data['cliente']);
        $pdf->Ln();

        // Adicionando informações dos itens
        foreach ($order_data['itens'] as $item) {
            $pdf->Cell(40, 10, 'Nome: ' . $item['nome']);
            $pdf->Ln();
            $pdf->Cell(40, 10, 'Valor: ' . $item['valor']);
            $pdf->Ln();
            $pdf->Cell(40, 10, 'Quantidade: ' . $item['quantidade']);
            $pdf->Ln();

        }

        // Adicionando valor total
        $pdf->Cell(40, 10, 'Total: ' . $order_data['total']['total_value']);

        // Salvando o arquivo
        $pdf->Output('F', 'pedido_' . $order_data['numero'] . '.pdf');
        $pdf->Output('D', 'pedido_' . $order_data['numero'] . '.pdf');

        
        if ($name_printer != null) {
            // Seleciona a impressora
            $printer = strval($name_printer);
            
            // Envia o arquivo para a impressora
            $file = ('pedido_' . $order_data['numero'] . '.pdf');
            passthru("lp -d $printer $file");
        }
    }
}
