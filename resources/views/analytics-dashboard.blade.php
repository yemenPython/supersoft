@extends('admin.layouts.app')

@section('title')
    <title>{{ __('Admin panel') }} </title>
@endsection

@section('style')

@endsection

@section('content')

@endsection

@section('modals')
    <div class="modal fade" id="boostrapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Work Card Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-sm waves-effect waves-light"
                            data-dismiss="modal">{{__('Close')}}</button>
                    <button type="button" class="btn btn-default waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__(' Print')}}
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="boostrapModal-sale" tabindex="-1" role="dialog" aria-labelledby="myModalLabel-1">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel-1">{{__('Sales Invoice')}}</h4>
                </div>

                <div class="modal-body" id="invoiceDatatoPrint-sale">
                </div>
                <div class="modal-footer" style="text-align:center">

                    <button type="button" class="btn btn-primary waves-effect waves-light"
                            onclick="printDownPayment()">
                        <i class='fa fa-print'></i>
                        {{__('Print')}}
                    </button>

                    <button type="button" class="btn btn-danger waves-effect waves-light"
                            data-dismiss="modal"><i class='fa fa-close'></i>
                        {{__('Close')}}</button>

                </div>

            </div>
        </div>
    </div>

@endsection

@section('js')
    <script type="application/javascript">
        function printDownPayment() {
            var element_id = 'card_invoice_print', page_title = document.title;
            print_element(element_id, page_title)
        }

        function getPrintData(id) {
            $.ajax({
                url: "{{ route('admin:card.invoices.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint").html(data.invoice);
                    let total = $("#totalInLetters").text();
                    $("#totalInLetters").html( new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function getSalePrintData(id) {
            $.ajax({
                url: "{{ route('admin:sales.invoices.show') }}?invoiceID=" + id,
                method: 'GET',
                success: function (data) {
                    $("#invoiceDatatoPrint-sale").html(data.invoice)
                    let selector = $('td[data-id="data-totalInLetters"]')
                    let total = selector.text()
                    selector.html(new Tafgeet(total, '{{env('DEFAULT_CURRENCY')}}').parse())
                }
            });
        }

        function global_search_redirect(input_id, url) {
            var input = $("#" + input_id).val()
            window.location = url + "?global_check=" + input
        }

        $(document).ready(function () {
            var labels = [], expenses = [], revenue = []
            @foreach($data['chart_analytics']['receipts'] as $index => $receipt)
            @php
                if ($index == 0) continue;
            @endphp
            labels.push("{{ __('analytics.month-' . ($index)) }}")
            expenses.push("{{ $receipt['expenses'] }}")
            revenue.push("{{ isset($receipt['revenue']) ? $receipt['revenue'] : 0 }}")
            @endforeach
            var receipts_config = {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('analytics.expenses-analytics') }}',
                        backgroundColor: 'rgb(54, 162, 235)',
                        borderColor: 'rgb(54, 162, 235)',
                        data: expenses,
                    }, {
                        label: '{{ __('analytics.revenue-analytics') }}',
                        backgroundColor: 'rgb(255, 99, 132)',
                        borderColor: 'rgb(255, 99, 132)',
                        data: revenue,
                    }]
                },
                options: {
                    responsive: true
                }
            };

            var ctx = document.getElementById('receipts-charts').getContext('2d');
            var chart = new Chart(ctx, receipts_config)

            var counters = [], counters_labels = [], amounts = [], amounts_labels = [], backgroundColors = []
            @foreach($invoices as $type => $data)
            backgroundColors.push("{{ $data['background'] }}")

            counters.push({{ $data['count'] }})
            counters_labels.push("{{ __('analytics.counts-invoices-' . $type) }}")

            amounts.push({{ $data['amount'] }})
            amounts_labels.push("{{ __('analytics.amounts-invoices-' . $type) }}")
            @endforeach

            var counter_config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: counters,
                        backgroundColor: backgroundColors
                    }],
                    labels: counters_labels
                },
                options: {
                    responsive: true
                }
            }
            var counter_ctx = document.getElementById('counters-charts').getContext('2d');
            var counter_chart = new Chart(counter_ctx, counter_config)

            var amount_config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: amounts,
                        backgroundColor: backgroundColors
                    }],
                    labels: amounts_labels
                },
                options: {
                    responsive: true
                }
            }

            var amount_ctx = document.getElementById('amounts-charts').getContext('2d');
            var amount_chart = new Chart(amount_ctx, amount_config)
        })
    </script>
@endsection
