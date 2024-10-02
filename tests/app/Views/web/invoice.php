<style>
    p, span, table { font-size: 10px}
    table { width: 100%; border: 1px solid #dee2e6; }
    table#tb-item tr th, table#tb-item tr td {
        border:1px solid #000
    }
</style>
<?php 
    $dateTime = new DateTime('now'); // Waktu sekarang
    $datenow = $dateTime->format('Y-m-d H:i:s'); 
?>
<h5 style="font-size:14pt;text-align:right">RESERVATION INVOICE</h5>
<span>Kepada Yth.</span><br/>
<table cellpadding="0" >
    <tr>
        <th width="10%">Name</th>
            <?php if (!empty($customer['fullname'])) : ?>
                <th width="40%">: <strong><?= esc($customer['fullname']); ?></strong></th>
            <?php else : ?>
                <th width="40%">: <strong>@<?= esc($customer['username']); ?></strong></th>
            <?php endif; ?>
        <th width="12%">No.Invoice</th>
        <th width="40%">: <strong><?= esc($detail['id']); ?></strong></th>
    </tr>
    <tr>
        <th width="10%">Addres</th>
            <?php if (!empty($customer['address'])) : ?>
                <th width="40%">: <strong><?= esc($customer['address']); ?></strong></th>
            <?php else : ?>
                <th width="40%">: <i>(profil is incomplete)</i></th>
            <?php endif; ?>
        <th width="12%">Request at</th>
        <?php $request_date = strtotime($detail['request_date']); ?>
        <th width="60%">: <strong><?= esc(date('l, j F Y H:i:s', $request_date)); ?></strong></th>
    </tr>
    <tr>
        <th width="10%">Telp</th>
            <?php if (!empty($customer['phone'])) : ?>
                <th width="40%">: <strong><?= esc($customer['phone']); ?></strong></th>
            <?php else : ?>
                <th width="40%">: <i>(profil is incomplete)</i></th>
            <?php endif; ?>
    </tr>
</table>
<br> <br>
<table id="tb-item" cellpadding="4" >
    <tr style="background-color:#a9a9a9">
        <th width="35%" style="height: 20px"><strong>Name Package</strong></th>
        <th width="12%" style="height: 20px"><strong>Capacity</strong></th>
        <th width="10%" style="height: 20px"><strong>Total People</strong></th>
        <th width="7%" style="height: 20px;text-align:center"><strong>Qty</strong></th>
        <th width="14%" style="height: 20px"><strong>Package Price</strong></th>
        <th width="18%" style="height: 20px"><strong>Total Price</strong></th>
    </tr>
    <tr>
        <td style="height: 20px">Package <?= esc($detail['name']); ?></td>
        <td style="height: 20px;text-align:center"><?= esc($data_package['min_capacity']); ?></td>
        <td style="height: 20px;"><?= esc($detail['total_people']); ?></td>
        <?php 
            $min=$data_package['min_capacity'];
            $people=$detail['total_people'];

            $jumlah_package = floor($people/$min);
            $tambahan =$people%$min;

            if($tambahan!=0){
                if ($tambahan <5){
                    $order= $jumlah_package+0.5;
                } else {
                    $order= $jumlah_package+1;
                }
            } else {
                $order= $jumlah_package;
            }
            $total_price_package = $order*$data_package['price'];
        ?>
        <td style="height: 20px;text-align:center"><?= $order; ?></td>
        <td style="height: 20px;text-align:right"><?= 'Rp'.number_format(esc($data_package['price']), 0, ',', '.'); ?></td>
        <td style="height: 20px;text-align:right"><?= 'Rp'.number_format(esc($total_price_package), 0, ',', '.'); ?></td>
    </tr>
    <tr><td colspan="6"></td></tr>
    <tr style="background-color:#a9a9a9">
        <th width="10%" style="height: 20px"><strong>Date</strong></th>
        <th width="15%" style="height: 20px"><strong>Homestay</strong></th>
        <th width="20%" style="height: 20px"><strong>Unit</strong></th>
        <th width="11%" style="height: 20px"><strong>Capacity</strong></th>
        <th width="7%" style="height: 20px"><strong>Days</strong></th>
        <th width="15%" style="height: 20px"><strong>Unit Price</strong></th>
        <th width="18%" style="height: 20px"><strong>Total Price</strong></th>
    </tr>
    <?php if (isset($booking)) : ?> 
        <?php foreach ($booking as $dtb) : ?>
            <?php $tothom = $dayhome*$dtb['price']; 
            ?>
            <tr>
                <td><?= esc(date('j F Y', strtotime($dtb['date']))); ?></td>
                <td><?= esc($dtb['name']); ?></td>
                <td><?= esc($dtb['name_type']); ?> <?= esc($dtb['unit_number']); ?> <?= esc($dtb['nama_unit']); ?></td>
                <td style="height: 20px;text-align:center"><?= esc($dtb['capacity']); ?></td>
                <td style="height: 20px;text-align:center"><?= esc($dayhome); ?></td>
                <td style="height: 20px;text-align:right"><?= 'Rp' . number_format(esc($dtb['price']), 0, ',', '.'); ?></td>
                <td style="height: 20px;text-align:right"><?= 'Rp' . number_format(esc($tothom), 0, ',', '.'); ?></td>
            </tr>              
        <?php endforeach; ?>
    <?php endif; ?>
    <tr style="border:1px solid #000">
        <td colspan="6" style="height: 20px"><strong>Grand Total</strong></td>
        <td style="height: 20px;text-align:right"><strong><?= 'Rp' . number_format(esc($detail['total_price']), 0, ',', '.'); ?></strong></td>
    </tr>
    <tr style="border:1px solid #000">
        <td colspan="6" style="height: 20px"><strong>Deposit</strong></td>
        <td style="height: 20px;text-align:right"><strong><?= 'Rp' . number_format(esc($detail['deposit']), 0, ',', '.'); ?></strong></td>
    </tr>
    <?php if($detail['refund_amount']): ?>
        <tr style="border:1px solid #000">
            <td colspan="6" style="height: 20px"><strong>Refund</strong></td>
            <td style="height: 20px;text-align:right"><strong><?= 'Rp' . number_format(esc($detail['refund_amount']), 0, ',', '.'); ?></strong></td>
        </tr>
    <?php endif; ?>
</table>
<br> <br>
<table>
    <tr>
        <th width="12%">Check In</th>
        <?php $check_in = strtotime($detail['check_in']); ?>
        <th width="60%">: <?= esc(date('l, j F Y H:i:s', $check_in)); ?></th>
    </tr>
    <tr>
        <th width="12%">Check Out</th>
        <th width="60%">: <?= esc(date('l, j F Y H:i:s', strtotime($check_out))); ?></th>
    </tr>
    <tr>
        <th width="25%"><b><u>Service Include</u></b></th>
        <th width="25%"><b><u>Service Exclude</u></b></th>
    </tr>
    <tr>
        <td>
            <?php if(!empty($serviceinclude)) : ?>    
                <?php foreach($serviceinclude as $se) : ?>
                    - <?= esc($se['name']); ?> <br>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
        <td>
            <?php if(!empty($serviceexclude)) : ?>
                <?php foreach($serviceexclude as $se) : ?>
                    - <?= esc($se['name']); ?><br>
                <?php endforeach; ?>
            <?php endif; ?>
        </td>
    </tr>
</table>
<br>

<table>
    <tr>
        <th width="98%"><b><u>Activity</u></b></th>
    </tr>
    <?php if(!empty($day)) : ?>
        <?php foreach ($day as $d) : ?>
        <tr>
            <td>Day <?= esc($d['day']);?><br>
            <?php if(!empty($activity)) : ?>
                <?php $i=1; ?>
                <?php foreach ($activity as $ac) : ?>
                    <?php if($d['day']==$ac['day']): ?>
                        <?= esc($i++); ?>. <?= esc($ac['name']);?> : <?= esc($ac['description']);?><br>
                    <?php endif; ?>
                <?php endforeach; ?></td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<p><u>TRANSFER VIA</u></p>
<p>Bank Syariah Mandiri (BSI) - Kode 451: IDR<br/>A/C : 73492379<br/>A/N : Green Talao Park</p>

<table cellpadding="0" >
        <?php $confirmation_date = strtotime($detail['confirmation_date']); ?>
        <?php $deposit_date = strtotime($detail['deposit_date']); ?>
        <?php $fullpayment_date = strtotime($detail['payment_date']); ?>
        <?php $refund_date = strtotime($detail['refund_date']); ?>
    <tr>
        <th width="25%">Confirmation  </th>
            <?php if (!empty($detail['confirmation_date'])) : ?>
                <th width="70%">: Confirmation on <strong><?= esc(date('l, j F Y H:i:s', $confirmation_date)); ?></strong> <br>(by admin <?= esc($detail['name_admin_confirm']);?>)</th>
            <?php else : ?>
                <th width="70%">: Incomplete</th>
            <?php endif; ?>
    </tr>
    <tr>
        <th width="25%">Deposit Payment </th>
            <?php if (!empty($detail['proof_of_deposit'])) : ?>
                <th width="70%">: Complete on <strong><?= esc(date('l, j F Y H:i:s', $deposit_date)); ?></strong> <br>(checked by admin <?= esc($detail['name_admin_deposit_check']);?>)</th>
            <?php else : ?>
                <th width="70%">: Incomplete</th>
            <?php endif; ?>
    </tr>
    <tr>
        <th width="25%">Full Payment  </th>
            <?php if (!empty($detail['proof_of_payment'])) : ?>
                <th width="70%">: Complete on <strong><?= esc(date('l, j F Y H:i:s', $fullpayment_date)); ?></strong> <br>(checked by admin <?= esc($detail['name_admin_payment_check']);?>)</th>
            <?php else : ?>
                <th width="70%">: Incomplete</th>
            <?php endif; ?>
    </tr>
    <?php if($detail['account_refund']!=null): ?>
    <tr>
        <th width="25%">Refund  </th>
            <?php if (!empty($detail['proof_refund'])) : ?>
                <th width="70%">: Complete on <strong><?= esc(date('l, j F Y H:i:s', $refund_date)); ?></strong> <br>(by admin <?= esc($detail['name_admin_refund']);?>)</th>
            <?php else : ?>
                <th width="70%">: Please wait, admin will send to your account</th>
            <?php endif; ?>
    </tr>
    <?php endif; ?>
    <br>
    <tr>
        <th width="25%"> Status  </th>
            <?php if($detail['status']==null && $detail['confirmation_date']==null && $detail['account_refund']==null): ?>    
                <th width="20%" style="background-color:#FFC436">: Waiting</th>
            <?php elseif($detail['status']==1): ?>    
                <th width="20%" style="background-color:#B6E388">: Accepted</th>
            <?php elseif($detail['status']==0): ?>    
                <th width="10%" style="background-color:#FFD3B0">: Rejected </th> 
                <th><i style="background-color:#FFFFFF"> (because <?= esc($detail['feedback']);?>)</i></th> 
            <?php endif; ?>      
    </tr>
        <?php if($detail['cancel']=='1'): ?>    
    <tr>
        <th width="25%">  </th>
            <?php if($detail['account_refund']==null): ?>    
                <th width="20%" style="background-color:#B4B4B3"> Cancel</th>
            <?php elseif($detail['account_refund']!=null): ?>    
                <th width="20%" style="background-color:#F78CA2"> Cancel and Refund</th>
            <?php endif; ?> 
    </tr>
        <?php endif; ?>  
</table>
<table cellpadding="4" >
    <tr>
        <td width="50%" style="height: 20px;text-align:center">
            <p>&nbsp;</p>
        </td>
        <td width="50%" style="height: 20px;text-align:center">
            <?php $date = date('Y-m-d H:i'); 
                  $date_now = strtotime($date);?>
            <p>Padang Pariaman, <?= esc(date('j F Y', $date_now)); ?></p>
            <p>Best regards,</p>
            <p></p>
            <p>Pokdarwis GTP Ulakan</p>
        </td>
    </tr>
</table>


