<table border="0" cellpadding="0" cellspacing="0" width="600" style="background-color:#fff;border:1px solid #dedede;border-radius:3px" bgcolor="#fff">
    <tbody>
      <tr>
        <td align="center" valign="top">
          <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#7f54b3;color:#fff;border-bottom:0;font-weight:bold;line-height:100%;vertical-align:middle;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;border-radius:3px 3px 0 0" bgcolor="#7f54b3">
            <tbody>
              <tr>
                <td style="padding:36px 48px;display:block">
                  <h1 style="font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;color:#fff;background-color:inherit" bgcolor="inherit">Confirm Order</h1>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="top">
          <table border="0" cellpadding="0" cellspacing="0" width="600">
            <tbody>
              <tr>
                <td valign="top" style="background-color:#fff" bgcolor="#fff">
                  <table border="0" cellpadding="20" cellspacing="0" width="100%">
                    <tbody>
                      <tr>
                        <td valign="top" style="padding:48px 48px 32px">
                          <div style="color:#636363;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left" align="left">
                            <p style="margin:0 0 16px">Hi {{ $order->fullname }},</p>
                            <p style="margin:0 0 16px">Thank you for booking your boat ticket. Your reservation has been confirmed and is currently being processed. Here's a summary of your booking::</p>
                            <h2 style="color:#7f54b3;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left"> 
                              [Order #{{ $order->code }}] ({{ date("j F, Y", strtotime($order->bookingDate)) }})
                            </h2>
                            <div style="margin-bottom:40px">
                              <table cellspacing="0" cellpadding="6" border="1" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" width="100%">
                                <thead>
                                  <tr>
                                    <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Product</th>
                                    <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Quantity</th>
                                    <th scope="col" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Price</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  @foreach ($order->orderTickets as $orderTicket)
                                    <tr>
                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;word-wrap:break-word" align="left">
                                          Transfer from {{ $orderTicket->fromLocationName }} to {{ $orderTicket->toLocationName }} by {{ $orderTicket->name }} <br>
                                          on {{ date("j F, Y", strtotime($orderTicket->departDate)) }} at {{ $orderTicket->departTime }} <br>
                                          People: {{ $order->adultQuantity + $order->childrenQuantity }}
                                      </td>
                                      <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left"> {{ $order->adultQuantity + $order->childrenQuantity }} </td>
                                      
                                      @if(!$orderTicket->agentName)
                                        <td style="color:#636363;border:1px solid #e5e5e5;padding:12px;text-align:left;vertical-align:middle;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif" align="left">
                                          <span><span>฿</span>{{ round($orderTicket->price) }} </span>
                                        </td>
                                      @endif
                                    </tr>
                                  @endforeach
                                </tbody>
                                <tfoot>
                                  <tr>
                                    <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left">Subtotal:</th>
                                    <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left">
                                      <span>
                                        <span>฿</span>{{ round($order->originalPrice) }} </span>
                                    </td>
                                  </tr>
                                  @if($order->couponAmount)
                                  <tr>
                                    <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left">Discount amount:</th>
                                    <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left;border-top-width:4px" align="left">
                                      <span>
                                        <span>฿</span>{{ round($order->couponAmount) }} </span>
                                    </td>
                                  </tr>
                                  @endif
                                  <tr>
                                    <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Payment method:</th>
                                    <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Bank transfer / โอนเงิน</td>
                                  </tr>
                                  <tr>
                                    <th scope="row" colspan="2" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">Total:</th>
                                    <td style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;padding:12px;text-align:left" align="left">
                                      <span>
                                        <span>฿</span>{{ round($order->finalPrice) }} </span>
                                    </td>
                                  </tr>
                                </tfoot>
                              </table>
                            </div>
                            <table cellspacing="0" cellpadding="0" border="0" style="width:100%;vertical-align:top;margin-bottom:40px;padding:0" width="100%">
                              <tbody>
                                <tr>
                                  <td valign="top" width="50%" style="text-align:left;font-family:'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif;border:0;padding:0" align="left">
                                    <h2 style="color:#7f54b3;display:block;font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:130%;margin:0 0 18px;text-align:left">Billing address</h2>
                                    <address style="padding:12px;color:#636363;border:1px solid #e5e5e5"> {{ $order->fullname }} <br>
                                      <!--Vietnam <br>-->
                                      <a href="tel:{{ $order->phone }}" style="color:#7f54b3;font-weight:normal;text-decoration:underline" target="_blank">
                                        {{ $order->phone }}
                                      </a>
                                      <br>
                                      <a href="mailto:{{ $order->email }}" target="_blank">{{ $order->email }}</a>
                                    </address>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                            
                            <p style="margin:0 0 16px">
                              <b>POLICY FOR CUSTOMER</b>
                            </p>
                            <p style="margin:0 0 16px">1. Destinations and Routes cannot be changed. <br> 2. Infant prices are available for infants aged between 0 – 4 years and will not be charged. <br> 3. Departure and arrival times can be subject to change due to weather conditions, SEUDAMGO reserves the right not to be liable for any such delays. <br> 4. Smoking is not permitted in the cabins of our boats or on any of our buses. <br> 5. No-shows: Customers must check-in at least 60 minutes prior to departure. Failure to check-in on time or in the case of missing the boat, the company reserves the right to no refunds. <br> 6. Cancellations: 50% will be refunded for cancellations 3 days before the departure date, thereafter there will be no refund. Notice of cancellation must be emailed to <a href="mailto:seudamgo@gmail.com" target="_blank">seudamgo@gmail.com</a>. <br> 7. Refund policy: For the faulty transaction, we will refund within 15 business days with the same as the original payment received method. <br> 8. Customers can park your car at our office in Trat. The service fee is just 50 THB/day/car. <br> 9. Maximum 20KG luggage allowed per passenger, excess luggage will be charged at 20 THB per KG not exceeding 30 KG. <br> 10. Water sports equipment (Such as short boards, longboards, skimboards, knee boards, windsurfing boards etc.) will be subject to a fee of 300 THB for travel by boat. <br> 11. Bicycles – will be subject to a fee of 500 THB (restricted to folding bicycles only) for transport by boat only. The company does not transport any form of motorcycle. <br> 12. Passengers can bring pets on boats by bringing one’s own standard cage. Only one pet per cage is allowed or without cage, passengers shall take care and tie chains or leash ropes throughout the trip. Pets shall never be released without a leash. Passengers are responsible for any damage to other passengers or their belongings caused by pets. Please contact our office/pier staff for further instructions and procedures to be followed. <br> 13. The schedule is subject to change without any prior notice in accordance with weather conditions for your safety. If SEUDAMGO knows in advance the weather on the day you would like to go on the boat, we will inform you as soon as possible. Customers can cancel the trip for a full refund. SEUDAMGO cannot be held responsible for adverse unforeseen weather conditions, involving rough seas and poor visibility. </p>
                            <p style="margin:0 0 16px">
                              <b>นโยบายสำหรับลูกค้า</b>
                            </p>
                            <p style="margin:0 0 16px">1. ลูกค้าไม่สามารถเปลี่ยนแปลงจุ <wbr>ดหมายปลายทางและเส้นทางได้ <br> 2. เด็กอายุต่ำกว่า 4 ขวบได้ใช้บริการโดยสารฟรี <br> 3. เวลาออกเดินทางและเวลามาถึ <wbr>งอาจเปลี่ยนแปลงได้เนื่ <wbr>องจากสภาพอากาศ SEUDAMGO ขอสงวนสิทธิ์ที่จะไม่รับผิ <wbr>ดชอบต่อความล่าช้าดังกล่าว <br> 4. ห้ามสูบบุหรี่ภายในห้ <wbr>องโดยสารเรือและรถรับส่ง <br> 5. การไม่แสดงตัว ท่านจะต้องมาถึงท่าเรือก่ <wbr>อนกำหนดเวลาเดินทางอย่างน้อย 60 นาที SEUDAMGO สงวนสิทธิ์ปฏิเสธการเดิ <wbr>นทางของท่านและไม่รับผิดชอบต่ <wbr>อความสูญเสียหรือความเสียหาย ในกรณีที่ลูกค้าเช็คอินไม่ <wbr>ตรงเวลา <br> 6. หลักเกณฑ์เกี่ยวกับอัตราการจ่ <wbr>ายเงินค่าบริการคืนให้แก่นักท่ <wbr>องเที่ยว กรณีนักท่องเที่ยวยกเลิกการเดิ <wbr>นทางมีดังนี้ <br> – แจ้งยกเลิกการเดินทางก่อนการเดิ <wbr>นทาง 3 วันจะได้เงินคืนครึ่งราคา <br> – แจ้งยกเลิกการเดินทางน้อยกว่า 3 วัน ไม่คืนเงิน <br> – กรุณาแจ้งการยกเลิกทางอีเมล <a href="mailto:seudamgo@gmail.com" target="_blank">seudamgo@gmail.com</a> เท่านั้น <br> 7. สำหรับธุรกรรมที่ผิดพลาด เราจะคืนเงินภายใน 15 วันทำการด้วยวิธีการชำระเงินเดิ <wbr>มที่ได้รับ <br> 8. ลูกค้าสามารถจอดรถได้ที่สำนั <wbr>กงานของเราในจังหวัดตราด ค่าบริการ 50 บาท/คัน/วัน <br> 9. ผู้โดยสารสามารถนำพาสัมภาระไม่ <wbr>เกิน 20 กิโลกรัม ท่านสามารถซื้อน้ำหนักสั <wbr>มภาระเพิ่มโดยค่าบริการคิดเป็น 20 บาท/กก สูงสุด 30 กิโลกรัม <br> 10. ผู้โดยสารสามารถนำอุปกรณ์กี <wbr>ฬาทางน้ำ เช่น กระดานสั้น ลองบอร์ด สกิมบอร์ด บอร์ดนั่ง บอร์ดนั่ง วินเซิร์ฟ เป็นต้น โดยค่าบริการคิดเป็น 300 บาท <br> 11. สำหรับจักรยาน มีค่าธรรมเนียมเป็น 500 บาทต่อคัน (เฉพาะจักรยานพับเท่านั้น) <br> 12. ผู้โดยสารสามารถนำสัตว์เลี้ <wbr>ยงของท่านโดยสารไปพร้อมกับท่ <wbr>านได้ <br> – สัตว์เลี้ยงต้องอยู่ในกรงหรื <wbr>อใส่สายจูงระหว่างการเดินทาง <br> – ผู้โดยสารต้องรับผิดชอบต่ <wbr>อความความสูญเสียหรือความเสี <wbr>ยหายต่อผู้โดยสารท่านอื่นที่เกิ <wbr>ดจากสัตว์เลี้ยงของท่าน <br> – SEUDAMGO จะไม่รับผิดชอบต่อการเจ็บป่วย บาดเจ็บ หรือเสียชีวิตของสัตว์เลี้ <wbr>ยงของท่าน ในระหว่างการเดินทาง <br> – โปรดติดต่อสำนักงาน/เจ้าหน้าที่ <wbr>ท่าเรือเพื่อขอคำแนะนำเพิ่มเติม <br> 13. ตารางเวลาอาจมีการเปลี่ <wbr>ยนแปลงโดยไม่แจ้งให้ทราบล่วงหน้ <wbr>าตามสภาพอากาศเพื่อความปลอดภั <wbr>ยของลูกค้า ถ้าหาก SEUDAMGO ทราบล่วงหน้าเรื่ <wbr>องสภาพอากาศในวันออกเรือ <br> SEUDAMGO จะแจ้งให้ลูกค้าทราบโดยเร็วที่ <wbr>สุด ลูกค้าสามารถขอรับเงินคืนเต็ <wbr>มจำนวนเมื่อยกเลิกการเดินทาง SEUDAMGO ขอไม่รับผิดชอบต่อสภาพอากาศที่ <wbr>ไม่คาดฝัน </p>
                            <p style="margin:0 0 16px">—– <br> We look forward to fulfilling your order soon. </p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>