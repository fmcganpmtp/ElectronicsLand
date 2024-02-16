		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<script type="text/javascript">
function print_fn() {
         window.onafterprint = window.close;
         window.print();
         
}
      </script>
                      <table class="table table-bordered" style="margin:10px auto; width:100%; border:1px solid #ccc; padding:15px; text-align:left; font-size:13px;   font-family: Arial, Helvetica, sans-serif;" >
                        <tbody>
                          <tr>
                            <th style="background:#3498db;padding:10px;color:#fff;border-bottom: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc;">Order Date </th>
                            <td style=" padding-left:10px;border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; border-top: 1px solid #ccc;">{{date('d - M - Y', strtotime($subscription->date))}}</td>
                          </tr>
                          <tr>
                               <th style="background:#3498db;padding:10px;color:#fff;border-bottom: 1px solid #ccc; border-left: 1px solid #ccc;">Order Status </th>
                            <td style="border-bottom: 1px solid #ccc; border-right: 1px solid #ccc;padding-left:10px;"><span class="act"><i class="fa fa-check-square-o" aria-hidden="true"></i>&nbsp;{{$subscription->status}}</span></td>
                          </tr>
                          <tr>
                               <th style="background:#3498db;padding:10px;color:#fff;border-bottom: 1px solid #ccc; border-left: 1px solid #ccc;">Date of payment </th>
                             <td style="border-bottom: 1px solid #ccc;border-right: 1px solid #ccc; padding-left:10px;">{{date('d - M - Y', strtotime($subscription->date))}}</td>
                          </tr>
                          <tr>
                               <th style="background:#3498db;padding:10px;color:#fff;border-bottom: 1px solid #ccc; border-left: 1px solid #ccc;">Billing Address </th>
                          <td style="border-bottom: 1px solid #ccc; border-right: 1px solid #ccc; padding-left:10px;"><ul style="padding:0px 0px 0px 15px;margin:0px 0px; display:table;">
                               <?php if(!empty($subscription->OrderDetail->name)) { ?><li>{{$subscription->OrderDetail->name}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->phone)) { ?><li> {{$subscription->OrderDetail->phone}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->address)) { ?> <li> {{$subscription->OrderDetail->address}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->city)) { ?> <li> {{$subscription->OrderDetail->city}}</li><?php } ?>
                               <?php if(!empty($subscription->OrderDetail->zip)) { ?> <li> {{$subscription->OrderDetail->zip}}</li><?php } ?>
                              </ul></td>
                          </tr>
                        </tbody>
                      </table>
                      
                      <button style="background:#333;padding:10px 15px; color:#fff;; border:none; border-radius:4px; margin:10px auto; display:table;"  onclick="document.title='Invoice';print_fn();">Print</button>
                      
                      <Style>
                           @media print {
         button { display:none !important;}
      }
                      </Style>
                      
                      
