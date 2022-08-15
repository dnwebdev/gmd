                  <div class="modal fade modal-ads modal-paid-ads" id="modal-paid" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span id="close-btn-modal-ads-info" class="float-right" aria-hidden="true">&times;</span>
                        </button>
                        <div class="modal-header">
                          <h5>{{trans('premium.modal-order.detail')}}</h5>
                          <span id="status-active">{{trans('premium.validation.paid')}}</span>
                        </div>
                        <div class="content-order-ads">
                          <table class="table table-borderless">
                            <tbody>
                              <thead class="title-table">
                                <tr>
                                  <th scope="col"></th>
                                  <th scope="col"></th>
                                  <th scope="col"></th>
                                  <th scope="col"></th>
                                </tr>
                              </thead>
                              <!-- <tr>
                                <th class="static-order-ads" scope="row">Nama Penerima</th>
                                <td class="dynamic-order-ads" id="username"></td>
                                <td class="static-order-ads">No Invoice</td>
                                <td class="dynamic-order-ads" id="no_invoice"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">Nama Perusahaan</th>
                                <td class="dynamic-order-ads" id="company"></td>
                                <td class="static-order-ads">Tanggal Pemesanan</td>
                                <td class="dynamic-order-ads" id="created_at"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">No Telpon</th>
                                <td class="dynamic-order-ads" id="phone_company"></td>
                                <td class="static-order-ads">Metode Pembayaran</td>
                                <td class="dynamic-order-ads" id="method_payment"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">Email</th>
                                <td class="dynamic-order-ads" id="company_email"></td>
                                <td class="static-order-ads">URL</td>
                                <td class="dynamic-order-ads" id="url"></td>
                                {{-- <td class="static-order-ads">Voucher</td>
                                <td class="dynamic-order-ads">PREMIUM96</td> --}}
                              </tr> -->
                              <tr>
                                <th class="static-order-ads" scope="row">{{trans('premium.modal-order.recipient')}}</th>
                                <td class="dynamic-order-ads" id="username"></td>
                                <!-- <td class="static-order-ads dekstop-ads-mobile">No Invoice</td>
                                <td class="dynamic-order-ads dekstop-ads-mobile" id="no_invoice"></td> -->
                              </tr>
                              <tr>
                                <td class="static-order-ads">{{trans('premium.modal-order.no-invoice')}}</td>
                                <td class="dynamic-order-ads" id="no_invoice"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">{{trans('premium.modal-order.company')}}</th>
                                <td class="dynamic-order-ads" id="company"></td>
                                <!-- <td class="static-order-ads dekstop-ads-mobile">Tanggal Pemesanan</td>
                                <td class="dynamic-order-ads dekstop-ads-mobile" id="created_at"></td> -->
                              </tr>
                              <tr>
                                <td class="static-order-ads">{{trans('premium.modal-order.date-order')}}</td>
                                <td class="dynamic-order-ads" id="created_at"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">{{trans('premium.modal-order.telp')}}</th>
                                <td class="dynamic-order-ads" id="phone_company"></td>
                                <!-- <td class="static-order-ads dekstop-ads-mobile">Metode Pembayaran</td>
                                <td class="dynamic-order-ads dekstop-ads-mobile" id="method_payment"></td> -->
                              </tr>
                              <tr>
                                <td class="static-order-ads">{{trans('premium.modal-order.payment-method')}}</td>
                                <td class="dynamic-order-ads " id="method_payment"></td>
                              </tr>
                              <tr>
                                <th class="static-order-ads" scope="row">Email</th>
                                <td class="dynamic-order-ads" id="company_email"></td>
                                <!-- <td class="static-order-ads dekstop-ads-mobile">URL</td>
                                <td class="dynamic-order-ads dekstop-ads-mobile" id="url"></td> -->
                                {{-- <td class="static-order-ads dekstop-ads-mobile">Voucher</td>
                                <td class="dynamic-order-ads dekstop-ads-mobile" id="voucher">PREMIUM96</td> --}}
                              </tr>
                              <tr>
                                <td class="static-order-ads">URL</td>
                                <td class="dynamic-order-ads" id="url"></td>
                              </tr>
                            </tbody>
                          </table>
                          <h5>Detail</h5>
                          <table class="detail-ordering table table-borderless">
                            <tbody>
                              <thead class="title-table">
                                <tr>
                                  <th scope="col">{{trans('premium.modal-order.product')}}</th>
                                  <th scope="col">{{trans('premium.modal-order.active-period')}}</th>
                                  <th scope="col">{{trans('premium.modal-order.price-total')}}</th>
                                </tr>
                              </thead>
                              <tr>
                                <th class="static-order-ads" scope="row" id="category_ads"></th>
                                <td class="dynamic-order-ads" id="date"></td>
                                <td class="static-order-ads total_price"></td>
                              </tr>                 
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>