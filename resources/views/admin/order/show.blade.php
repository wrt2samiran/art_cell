@extends('admin.layouts.after-login-layout')


@section('unique-content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Order Management</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
              <li class="breadcrumb-item"><a href="{{route('admin.order.list')}}">Order</a></li>
              <li class="breadcrumb-item active">Details</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
          <div class="row">
          <div class="col-12">
            <!-- Default box -->
            <div class="card card-success">
                <div class="card-header">
                Order Details
                </div> 
              <div class="card-body"> 
                 <table class="table table-bordered table-hover record-details-table" id="service-provider-details-table">
                      <tbody>
                        <tr>
                          <td>Job No</td>
                          <td >{{@$order->job_no}}</td>
                        </tr>
                        <tr>
                          <td>First Name</td>
                          <td >{{@$order->first_name}}</td>
                        </tr>
                        <tr>
                          <td >Last Name</td>
                          <td >{{@$order->last_name}}</td>
                        </tr>
                        <tr>
                          <td >Phone/Contact Number</td>
                          <td >{{@$order->customer_contact}}</td>
                        </tr>
                        <tr>
                          <td >Mobile Brand</td>
                          <td >{{@$order->mobile_brand->name}}</td>
                        </tr>
                        <tr>
                          <td >Model</td>
                          <td >{{@$order->mobile_brand_model->name}}</td>
                        </tr>
                        <tr>
                          <td >IMEI/Serial No</td>
                          <td >{{@$order->imei_serial}}</td>
                        </tr>
                        <tr>
                          <td >Warrenty No</td>
                          <td >{{@$order->warrrenty_no}}</td>
                        </tr>
                         <tr>
                          <td >Country</td>
                          <td >{{@$order->country->name}}</td>
                        </tr>
                        <tr>
                          <td >State</td>
                          <td >{{@$order->state->name}}</td>
                        </tr>
                        <tr>
                          <td >City</td>
                          <td >{{@$order->city->name}}</td>
                        </tr>
                        <tr>
                          <td >Address</td>
                           <td> {{@$order->customer_address}} </td>  
                        </tr>
                        <tr>
                          <td >Physical Condition</td>
                          <td >{{@$order->physical_condition}} </td>
                        </tr>

                        <tr>
                          <td >Risk Agreed By Customer</td>
                          <td >{{@$order->risk_agreed_by_customer}} </td>
                        </tr>

                        <tr>
                          <td >Service Complaints</td>
                          <td >{{@$order->service_complaints}} </td>
                        </tr>
                        
                        <tr>
                          <td >Estimated Price</td>
                          <td >{{@$order->estimated_price}} </td>
                        </tr>

                        <tr>
                          <td >Advanced Payment</td>
                          <td >{{@$order->advanced_payment}} </td>
                        </tr>

                        <tr>
                          <td >Due Payment</td>
                          <td >{{@$order->due_payment}} </td>
                        </tr>

                        <tr>
                          <td>Status</td>
                          <td>
                            <button role="button" class="btn btn-{{($order->is_active=='1')?'success':'danger'}}">{{($order->is_active=='1')?'Active':'Inactive'}}</button>
                          </td>
                        </tr>

                        <tr>
                          <td>Created At</td>
                          <td>{{$order->created_at->format('d/m/Y')}}</td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"><a class="btn btn-primary" href="{{route('admin.order.list')}}"><i class="fas fa-backward"></i>&nbsp;Back</a><button onclick="printInvoice()" class="btn btn-success">Print</button> </td>
                        </tr>
                      </tfoot>
                  </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </section>
</div>
@endsection 



<style>
    .invoice-box {
        max-width: 800px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, .15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
    
    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }
    
    .invoice-box table td {
        padding: 5px;
        vertical-align: top;
    }
    
    .invoice-box table tr td:nth-child(2) {
        text-align: right;
    }
    
    .invoice-box table tr.top table td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.top table td.title {
        font-size: 45px;
        line-height: 45px;
        color: #333;
    }
    
  /*  .invoice-box table tr.information table td {
        padding-bottom: 40px;
    }*/
    
    .invoice-box table tr.heading td {
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }
    
    .invoice-box table tr.details td {
        padding-bottom: 20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom: 1px solid #eee;
    }
    
    .invoice-box table tr.item.last td {
        border-bottom: none;
    }
    
    .invoice-box table tr.total td:nth-child(2) {
        border-top: 2px solid #eee;
        font-weight: bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td {
            width: 100%;
            display: block;
            text-align: center;
        }
        
        .invoice-box table tr.information table td {
            width: 100%;
            display: block;
            text-align: center;
        }
    }
    
    /** RTL **/
    .rtl {
        direction: rtl;
        font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
    }
    
    .rtl table {
        text-align: right;
    }
    
    .rtl table tr td:nth-child(2) {
        text-align: left;
    }
    </style>
    <div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog" style="width:1250px; !important">
      <!-- Modal content-->
        <div class="modal-content">
          <div class="invoice-box">
              <table cellpadding="0" cellspacing="0">
                  <tr class="top">
                      <td colspan="2">
                          <table>
                              <tr>
                                  <td class="title">
                                      <img src="{{asset('assets/dist/img/act_call_logo.png')}}" style="width:15%; max-width:300px;">
                                  </td>
                                  <td>
                                      Art Cell<br>
                                      Imran Bhai<br>
                                      4 Chandni approach<br>
                                      Near morden eating house (new smart tech)<br>
                                      Kolkata 700072
                                  </td>
                                  
                              </tr>
                          </table>
                      </td>
                  </tr>

                  
                  <tr class="information">
                      <td colspan="2">
                          <table>
                              <tr>
                                 <td>
                                      <table style="border: solid;">
                                        <tr>
                                          <td>Job Number: {{@$order->job_no}}</td>
                                          <td>Ref Number: {{@$order->ref_no}}</td>
                                          <td>Warrenty Number: {{@$order->warrrenty_no}}</td>
                                          <td>Date: {{@$order->created_at}}</td>
                                        </tr>
                                      </table>
                                  </td> 
                                  
                                  
                              </tr>
                          </table>
                      </td>
                  </tr>
                  <tr class="information">
                    <td colspan="2">
                      <table>
                        <tr>
                          <td style="border: solid; width: 50%; text-align:left;">
                            <strong>Customer Information</strong></br>
                            Name: {{@$order->first_name}} {{@$order->last_name}}</br>
                            Contact: {{@$order->customer_contact}}</br>
                            <div>Address: {{@$order->customer_address}}</div><div> {{@$order->city->name}}, {{@$order->state->name}}, {{@$order->country->name}}</div></br>


                          </td>
                          <td style="border: solid; width: 50%; text-align:left;">
                            <strong>Device Information</strong></br>
                            Company: {{@$order->mobile_brand->name}}</br>
                            Model: {{@$order->mobile_brand_model->name}}</br>
                            IMEI / Serial: {{@$order->imei_serial}}</br>
                          </td>
                        </tr>
                      </table>
                  </tr>
                  <tr class="heading">
                      <td colspan="2">
                          Physical Condition
                      </td>
                     
                  </tr>
                  
                  <tr class="details">
                      <td colspan="2">
                          <div>{{@$order->physical_condition}}</div>
                      </td>
                     
                  </tr>

                  <tr class="heading">
                      <td colspan="2">
                          Risk Agreed by Customer
                      </td>
                     
                  </tr>
                  
                  <tr class="details">
                      <td colspan="2">
                          <div>{{@$order->risk_agreed_by_customer}}</div>
                      </td>
                     
                  </tr>


                  <tr class="heading">
                      <td colspan="2">
                          Service Complaints
                      </td>
                     
                  </tr>
                  
                  <tr class="details">
                      <td colspan="2">
                          <div>{{@$order->service_complaints}}</div>
                      </td>
                     
                  </tr>

                  <tr class="information">
                      <td colspan="2">
                          <table>
                              <tr>
                                 <td>
                                      <table style="border: solid;">
                                        <tr>
                                          <td>Estimate: {{@$order->estimated_price}}</td>
                                          <td>Advance Paid: {{@$order->advanced_payment}}</td>
                                          <td>Due Date: </td>
                                          <td>Entry Stuff: {{@$order->createdby->name}}</td>
                                        </tr>
                                      </table>
                                  </td> 
                                  
                                  
                              </tr>
                          </table>
                      </td>
                  </tr>

                  <tr class="heading">
                      <td colspan="2">
                          Terms & Conditions:
                      </td>
                     
                  </tr>
                  
                  <tr class="details">
                      <td colspan="2">
                          <div>1. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
                          <div>2. Quisque nisl eros, pulvinar facilisis justo mollis, auctor consequat urna.</div>
                          <div>3. Morbi a bibendum metus. </div>
                          <div>4. Donec scelerisque sollicitudin enim eu venenatis.</div>
                          <div>5. Integer eu nibh at nisi ullamcorper sagittis id vel leo.</div>
                      </td>
                     
                  </tr>


                  <tr class="details">
                      <td colspan="2" style="text-align: right;">
                          Signature
                      </td>
                     
                  </tr>
              </table>
              <a class="btn btn-primary" href="javascript:void(0);" onclick="printArea2();" >Print</a>
          </div>

        </div>
      </div>
    </div>
    <script type="text/javascript">
      function printInvoice()
      {
        $("#myModal").modal();
      }

      function printArea2() {
        var contents = document.getElementById("myModal").innerHTML;
        var frame1 = document.createElement('iframe');
        frame1.name = "frame1";
        frame1.style.position = "absolute";
        frame1.style.top = "-1000000px";
        document.body.appendChild(frame1);
        var frameDoc = frame1.contentWindow ? frame1.contentWindow : frame1.contentDocument.document ? frame1.contentDocument.document : frame1.contentDocument;
        frameDoc.document.open();
        frameDoc.document.write('<html><head><title>DIV Contents</title>');
        frameDoc.document.write('</head><body>');
        frameDoc.document.write(contents);
        frameDoc.document.write('</body></html>');
        frameDoc.document.close();
        setTimeout(function () {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            document.body.removeChild(frame1);
        }, 500);
        return false;
    }
    </script>