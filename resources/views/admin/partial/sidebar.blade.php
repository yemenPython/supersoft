<div class="main-menu {{ authIsSuperAdmin() ? 'admin' : 'supervisor' }}-side-bar">
    <header class="header">
        <a href="{{route('admin:home')}}" class="logo"><img src="{{url('default-images/logo.png')}}"
                                                            style="width:40px;height:40px;margin:0 10px;border-radius:50%">{{__('Super Car')}}
        </a>
        <button type="button" class="button-close fa fa-times js__menu_close"></button>

    </header>
    <!-- /.header -->
    <div class="content">

        <div class="navigation">
            <ul class="menu js__accordion">
                <li class="">
                    <a class=" " href="{{route('admin:home')}}">
                        <i style="color:#336BAB !important"
                           class="menu-icon fa fa-dashboard"></i><span>{{__('Dashboard')}}</span></a>
                </li>
                <li>
                    <a class="  parent-item js__control" href="#"><i style="color:#33AB9B !important"
                                                                     class="menu-icon fa fa-user">
                        </i><span>{{__('Users Management')}}</span><span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

                        <li class=""><a href="{{route('admin:users.index')}}">{{__('Users')}}</a></li>


                        <li class=""><a href="{{route('admin:roles.index')}}">{{__('Roles')}}</a></li>


                        <li class=""><a href="{{route('admin:activity.index')}}">{{__('Activity')}}</a></li>

                    </ul>
                </li>
                <li class="{{setActivationClass([
route('admin:notification.settings.edit'),
url('admin/currencies'),
url('admin/countries'),
url('admin/cities'),
url('admin/areas'),
url('admin/branches'),
url('admin/taxes'),
route('admin:settings.edit'),
route('admin:notification.settings.edit'),
route('admin:mail.settings.edit'),
route('admin:sms.settings.edit'),
route('admin:points.settings.edit'),
route('admin:points-rules.index'),
route('admin:supply-terms.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#"><i
                            class="menu-icon fa fa-gear"></i><span>{{__('Setting')}}</span><span
                            class="menu-arrow fa fa-angle-down"></span></a>
                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(url('admin/currencies'))}}">
                            <a href="{{url('admin/currencies')}}">{{__('currencies')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/currencies'))}}">
                            <a href="{{url('admin/countries')}}">{{__('countries')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/cities'))}}">
                            <a href="{{url('admin/cities')}}">{{__('cities')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/areas'))}}">
                            <a href="{{url('admin/areas')}}">{{__('areas')}}</a>
                        </li>

                        {{--                        @if(authIsSuperAdmin())--}}
                        <li class="{{setActivationClass(url('admin/branches'))}}">
                            <a href="{{url('admin/branches')}}">{{__('branches')}}</a>
                        </li>
                        {{--                        @endif--}}


                        <li class="{{setActivationClass(url('admin/taxes'))}}">
                            <a href="{{url('admin/taxes')}}">{{__('Taxes And Fees')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:db-backup'))}}">
                            <a class=" " href="{{route('admin:db-backup')}}">
                                {{__('words.db-backup')}}</a>
                        </li>

                        @if(!authIsSuperAdmin())
                            <li class="{{setActivationClass(route('admin:settings.edit'))}}">
                                <a class=" " href="{{route('admin:settings.edit')}}">
                                    {{__('Global settings')}}</a>
                            </li>
                        @endif

                        @if(!authIsSuperAdmin())
                            <li class="{{setActivationClass(route('admin:notification.settings.edit'))}}">
                                <a class="" href="{{route('admin:notification.settings.edit')}}">
                                    {{__('Notification settings')}}</a>
                            </li>
                        @endif

                        @if(!authIsSuperAdmin())
                            <li class="{{setActivationClass(route('admin:mail.settings.edit'))}}">
                                <a class=" " href="{{route('admin:mail.settings.edit')}}">
                                    {{__('Mail settings')}}</a>
                            </li>
                        @endif

                        @if(!authIsSuperAdmin())
                            <li class="{{setActivationClass(route('admin:sms.settings.edit'))}}">
                                <a class=" " href="{{route('admin:sms.settings.edit')}}">
                                    {{__('Sms settings')}}</a>
                            </li>
                    @endif

                    <!-- @if(!authIsSuperAdmin())
                        <li class="{{setActivationClass(route('admin:points.settings.edit'))}}">
                                <a class=" " href="{{route('admin:points.settings.edit')}}">
                                    {{__('Points settings')}}</a>
                            </li>
                        @endif

                        <li class="{{setActivationClass(route('admin:points-rules.index'))}}">
                            <a href="{{route('admin:points-rules.index')}}">{{__('Points Rules')}}</a>
                        </li> -->

                        <li class="{{setActivationClass(route('admin:supply-terms.index'))}}">
                            <a href="{{route('admin:supply-terms.index')}}">{{__('Supply & payments')}}</a>
                        </li>

                    </ul>
                </li>
                <li class="{{setActivationClass([
url('admin/stores'),
route('admin:stores-transfers.index'),
route('admin:part-types'),
url('admin/spare-part-units'),
route('admin:parts.index'),
route('admin:opening-balance.index'),
route('admin:concession-types.index'),
route('admin:concessions.index'),
route('admin:supply-orders.index'),
route('admin:purchase.quotations.compare.index'),
route('admin:purchase-receipts.index'),
route('admin:sale-quotations.index'),
route('admin:sale-supply-orders.index'),
route('admin:sales.invoices.index'),
route('admin:maintenance-cards.index'),
route('admin:return-sale-receipts.index'),
route('admin:sales.invoices.return.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#"><i style="color:#C3333C !important"
                                                                   class="menu-icon fa fa-building-o"></i><span>{{__('Items management')}}</span><span
                            class="menu-arrow fa fa-angle-down"></span></a>
                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(url('admin/stores'))}}">
                            <a href="{{url('admin/stores')}}">{{__('Stores')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:part-types'))}}">
                            <a href="{{route('admin:part-types')}}">{{__('words.part-types')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/spare-part-units'))}}">
                            <a href="{{url('admin/spare-part-units')}}">{{__('Spare parts units')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:parts.index'))}}">
                            <a href="{{route('admin:parts.index')}}">{{__('Parts management')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:opening-balance.index'))}}">
                            <a href="{{route('admin:opening-balance.index')}}">{{__('opening-balance.index-title')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:stores-transfers.index'))}}">
                            <a href="{{ route('admin:stores-transfers.index') }}">{{__('words.stores-transfers')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:damaged-stock.index'))}}">
                            <a class=" " href="{{route('admin:damaged-stock.index')}}">
                                <span>{{__('Damaged Stock')}}</span></a>
                        </li>
                        <li class="{{setActivationClass(route('admin:settlements.index'))}}">
                            <a class=" " href="{{route('admin:settlements.index')}}">
                                <span>{{__('Settlements')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:concession-types.index'))}}">
                            <a class=" " href="{{route('admin:concession-types.index')}}">
                                <span>{{__('Concession Types')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:concessions.index'))}}">
                            <a class=" " href="{{route('admin:concessions.index')}}">
                                <span>{{__('Concessions')}}</span></a>
                        </li>


                        <li class="{{setActivationClass(route('admin:purchase-requests.index'))}}">
                            <a class=" " href="{{route('admin:purchase-requests.index')}}">
                                <span>{{__('Purchase Requests')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase-quotations.index'))}}">
                            <a class=" " href="{{route('admin:purchase-quotations.index')}}">
                                <span>{{__('Purchase Quotations')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase.quotations.compare.index'))}}">
                            <a class=" " href="{{route('admin:purchase.quotations.compare.index')}}">
                                <span>{{__('Purchase Quotations Compare')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:supply-orders.index'))}}">
                            <a class=" " href="{{route('admin:supply-orders.index')}}">
                                <span>{{__('Supply Orders')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase-receipts.index'))}}">
                            <a class=" " href="{{route('admin:purchase-receipts.index')}}">
                                <span>{{__('Purchase Receipts')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase-invoices.index'))}}">
                            <a href="{{route('admin:purchase-invoices.index')}}">
                                {{__('Purchase Invoices')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase_returns.index'))}}">
                            <a href="{{route('admin:purchase_returns.index')}}">
                                {{__('Purchase Invoices Returns')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:sale-quotations.index'))}}">
                            <a href="{{route('admin:sale-quotations.index')}}">
                                {{__('Sale Quotations')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:sale-supply-orders.index'))}}">
                            <a href="{{route('admin:sale-supply-orders.index')}}">
                                {{__('Sale Supply Orders')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:sales.invoices.index'))}}">
                            <a href="{{route('admin:sales.invoices.index')}}">
                                {{__('Sale Invoices')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:return-sale-receipts.index'))}}">
                            <a href="{{route('admin:return-sale-receipts.index')}}">
                                {{__('Return Sales Receipts')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:sales.invoices.return.index'))}}">
                            <a href="{{route('admin:sales.invoices.return.index')}}">
                                {{__('Sales Invoice Return')}}
                            </a>
                        </li>


                    </ul>
                </li>


                <li class="{{setActivationClass([
route('admin:assetsType.index'),
route('admin:assetsGroup.index'),
route('admin:assets.index'),
route('admin:assets_expenses_types.index'),
route('admin:assets_expenses_items.index'),
route('admin:assets_expenses.index'),
route('admin:purchase-assets.index'),
route('admin:opening-balance-assets.index'),
route('admin:assets_replacements.index'),
route('admin:consumption-assets.index'),
route('admin:sale-assets.index'),
route('admin:stop_and_activate_assets.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#">
                        <i style="color:#0E73D2 !important" class="menu-icon fa fa-folder-open-o"></i>
                        <span>{{__('Assets')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(route('admin:assetsType.index'))}}">
                            <a href="{{route('admin:assetsType.index')}}">
                                {{__('Assets Types')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:assetsGroup.index'))}}">
                            <a href="{{route('admin:assetsGroup.index')}}">
                                {{__('Assets Groups')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:assets.index'))}}">
                            <a href="{{route('admin:assets.index')}}">
                                {{__('Assets data')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:purchase-assets.index'))}}">
                            <a href="{{route('admin:purchase-assets.index')}}">
                                {{__('Purchase Assets')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:opening-balance-assets.index'))}}">
                            <a href="{{route('admin:opening-balance-assets.index')}}">
                                {{__('Opening Balance Assets')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:assets_expenses_types.index'))}}">
                            <a href="{{route('admin:assets_expenses_types.index')}}">
                                {{__('Assets Expenses Types')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:assets_expenses_items.index'))}}">
                            <a href="{{route('admin:assets_expenses_items.index')}}">
                                {{__('Assets Expenses Items')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:assets_expenses.index'))}}">
                            <a href="{{route('admin:assets_expenses.index')}}">
                                {{__('Assets Expenses')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:assets_replacements.index'))}}">
                            <a href="{{route('admin:assets_replacements.index')}}">
                                {{__('Assets Replacements')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:consumption-assets.index'))}}">
                            <a href="{{route('admin:consumption-assets.index')}}">
                                {{__('Assets Consumption')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:sale-assets.index'))}}">
                            <a href="{{route('admin:sale-assets.index')}}">
                                {{__('Assets Sale')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:stop_and_activate_assets.index'))}}">
                            <a href="{{route('admin:stop_and_activate_assets.index')}}">
                                {{__('Stop And Activate Assets')}}
                            </a>
                        </li>

                    </ul>
                </li>



                <li class="{{setActivationClass([
route('admin:services-types.index'),
route('admin:services.index'),
route('admin:services_packages.index'),
route('admin:maintenance_centers.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i style="color:#5DA971 !important"
                                                                     class="menu-icon fa fa-cogs"></i>
                        <span>{{__('Services and sections')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">
                        <li class="{{setActivationClass(route('admin:maintenance_centers.index'))}}">
                            <a href="{{route('admin:maintenance_centers.index')}}">{{__('Maintenance centers')}}</a>
                        </li>


                        <li class="{{setActivationClass(route('admin:services-types.index'))}}">
                            <a href="{{route('admin:services-types.index')}}">{{__('Services Types')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:services.index'))}}">
                            <a href="{{route('admin:services.index')}}">{{__('Services')}}</a>
                        </li>

                        <li class="{{setActivationClass(route('admin:services_packages.index'))}}">
                            <a href="{{route('admin:services_packages.index')}}">{{__('Services Packages')}}</a>
                        </li>


                        <li class="{{setActivationClass(route('admin:maintenance-detection-types.index'))}}">
                            <a href="{{route('admin:maintenance-detection-types.index')}}">
                                {{__('Maintenance Types')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:maintenance-detections.index'))}}">
                            <a href="{{route('admin:maintenance-detections.index')}}">
                                {{__('Maintenance Detection')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:maintenance-cards.index'))}}">
                            <a href="{{route('admin:maintenance-cards.index')}}">
                                {{__('Maintenance Cards')}}
                            </a>
                        </li>
                    <!--
                        <li class="{{setActivationClass(url('admin/companies'))}}">
                            <a href="{{url('admin/companies')}}">{{__('Companies')}}</a>
                        </li>


                        <li class="{{setActivationClass(url('admin/carModels'))}}">
                            <a href="{{url('admin/carModels')}}">{{__('Cars Models')}}</a>
                        </li>


                        <li class="{{setActivationClass(url('admin/carTypes'))}}">
                            <a href="{{url('admin/carTypes')}}">{{__('Cars Types')}}</a>
                        </li> -->

                        <li class="{{setActivationClass(route('admin:work-cards.index'))}}">
                            <a class=" " href="{{route('admin:work-cards.index')}}">
                                <span>{{__('Car Maintainance')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:maintenance.status.index.report'))}}">
                            <a class=" " href="{{route('admin:maintenance.status.index.report')}}">
                                <span>{{__('Maintenance Status')}}</span>
                            </a>
                        </li>
                    <!-- <li class="{{setActivationClass(route('admin:reservations.index'))}}">
                            <a class=" " href="{{route('admin:reservations.index')}}">
                                <span>{{__('Services Reservations')}}</span>
                            </a>
                        </li> -->

                    </ul>
                </li>


                <li class="{{setActivationClass([
route('admin:supplier-group-tree'),
route('admin:suppliers.index'),
route('admin:customers-categories.index'),
route('admin:customers.index'),
route('admin:customers.requests.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i style="color:#587EB4 !important"
                                                                     class="menu-icon fa fa-user"></i>
                        <span>{{__('Suppliers and customers')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">


                        <li class="{{setActivationClass(route('admin:supplier-group-tree'))}}">
                            <a href="{{route('admin:supplier-group-tree')}}">
                                {{__('Suppliers Groups')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:maintenance-detections.index'))}}">
                            <a href="{{route('admin:suppliers.index')}}">
                                {{__('Suppliers data')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:customers-categories.index'))}}">
                            <a href="{{route('admin:customers-categories.index')}}">
                                {{__('customers Categories')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:customers.index'))}}">
                            <a href="{{route('admin:customers.index')}}">{{__('Customers data')}}</a>
                        </li>

                    <!--
                        <li class="{{setActivationClass(route('admin:customers.requests.index'))}}">
                            <a href="{{route('admin:customers.requests.index')}}">{{__('Customers Requests')}}</a>
                        </li> -->

                    </ul>
                </li>


            <!-- <li class="{{setActivationClass([
url('admin/companies'),
url('admin/carModels'),
url('admin/carTypes'),
route('admin:maintenance-detection-types.index'),
route('admin:maintenance-detections.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i style="color:#A95D62 !important"
                                                                     class="menu-icon fa fa-server"></i>
                        <span>{{__('Maintenance')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">





                    </ul>
                </li> -->


                <li class="{{setActivationClass([
route('admin:financial_management.type_revenue.index'),
route('admin:financial_management.type_expenses.index'),
route('admin:banks.banks_accounts.index'),
route('admin:banks.opening_balance_accounts.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#">
                        <i style="color:#0E73D2 !important" class="menu-icon fa fa-folder-open-o"></i>
                        <span>{{__('Financial Management')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(route('admin:financial_management.type_revenue.index'))}}">
                            <a href="{{route('admin:financial_management.type_revenue.index')}}">
                              {{__('Types Revenues')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:financial_management.type_expenses.index'))}}">
                            <a href="{{route('admin:financial_management.type_expenses.index')}}">
                                 {{__('Expense types')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:financial_management.tof_type_expenses.index'))}}">
                            <a href="{{route('admin:financial_management.tof_type_expenses.index')}}">
                                {{__('Expenses dues types')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:financial_management.tof_type_revenues.index'))}}">
                            <a href="{{route('admin:financial_management.tof_type_revenues.index')}}">
                                {{__('Revenue dues types')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:banks.bank_data.index'))}}">
                            <a href="{{route('admin:banks.bank_data.index')}}">
                                {{__('Banks Data')}}
                            </a>
                        </li>


                        <li class="{{setActivationClass(route('admin:banks.branch_product.index'))}}">
                            <a href="{{route('admin:banks.branch_product.index')}}">
                                {{__('branch products')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:banks.type_bank_accounts.index'))}}">
                            <a href="{{route('admin:banks.type_bank_accounts.index')}}">
                                {{__('Types Bank Accounts')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:banks.banks_accounts.index'))}}">
                            <a href="{{route('admin:banks.banks_accounts.index')}}">
                                {{__('Accounts')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:banks.opening_balance_accounts.index'))}}">
                            <a href="{{route('admin:banks.opening_balance_accounts.index')}}">
                                {{__('Bank Accounts Opening Balance')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:lockers.index'))}}">
                            <a href="{{route('admin:lockers.index')}}">
                                {{__('Lockers')}}
                </a>
            </li>

                        <li class="{{setActivationClass(route('admin:lockers_opening_balance.index'))}}">
                            <a href="{{route('admin:lockers_opening_balance.index')}}">
                                {{__('Locker Opening Balance')}}
                            </a>
                        </li>

                        <li class="{{setActivationClass(route('admin:customers.index'))}}">
                            <a href="{{route('admin:lockers-transfer.index')}}">
                                {{__('Lockers Transfer')}}
                </a>
            </li>
            <li class="{{setActivationClass(route('admin:locker-exchanges.index'))}}">
                            <a href="{{route('admin:locker-exchanges.index')}}">
                                {{ __('words.locker-exchanges') }}
                </a>
            </li>
            <li class="{{setActivationClass(route('admin:locker-receives.index'))}}">
                            <a href="{{route('admin:locker-receives.index')}}">
                                {{ __('words.locker-receives') }}
                </a>
            </li>
                    </ul>
                </li>


              <li class="{{setActivationClass([

route('admin:accounts.index'),
route('admin:lockers-transfer.index'),
route('admin:accounts-transfer.index'),
route('admin:lockers-transactions.index'),

route('admin:bank-exchanges.index'),
route('admin:bank-receives.index'),
route('admin:lockers_opening_balance.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#">
                        <i style="color:#0E73D2 !important" class="menu-icon fa fa-folder-open-o"></i>
                        <span>{{__('Lockers and Banks')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

            <li class="{{setActivationClass(route('admin:accounts.index'))}}">
                            <a href="{{route('admin:accounts.index')}}">
                                {{__('Accounts')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:accounts-transfer.index'))}}">
                            <a href="{{route('admin:accounts-transfer.index')}}">
                                {{__('Accounts Transfer')}}
                </a>
            </li>
            <li class="{{setActivationClass(route('admin:lockers-transactions.index'))}}">
                            <a href="{{route('admin:lockers-transactions.index')}}">
                                {{__('words.locker-transaction')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:bank-exchanges.index'))}}">
                            <a href="{{route('admin:bank-exchanges.index')}}">
                                {{ __('words.bank-exchanges') }}
                </a>
            </li>
            <li class="{{setActivationClass(route('admin:bank-receives.index'))}}">
                            <a href="{{route('admin:bank-receives.index')}}">
                                {{ __('words.bank-receives') }}

                            </a>
                        </li>
                    </ul>
                </li>


                <li class="
route('admin:revenues_types.index'),
route('admin:revenues_Items.index'),
route('admin:revenueReceipts.index'),
route('admin:expenses_types.index'),
route('admin:expenses_items.index'),
route('admin:expenseReceipts.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i style="color:#556EF6 !important"
                                                                     class="menu-icon fa fa-money"></i>
                        <span>{{__('Revenues and Expenses')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(route('admin:revenues_types.index'))}}">
                            <a href="{{route('admin:revenues_types.index')}}">
                                {{__('Revenues Types')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:bank-receives.index'))}}">
                            <a href="{{route('admin:revenues_Items.index')}}">
                                {{__('Revenues Items')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:revenueReceipts.index'))}}">
                            <a href="{{route('admin:revenueReceipts.index')}}">
                                {{__('Revenues Receipts')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:bank-receives.index'))}}">
                            <a href="{{route('admin:expenses_types.index')}}">
                                {{__('Expenses Types')}}
                </a>
            </li>


            <li class="{{setActivationClass(route('admin:expenses_items.index'))}}">
                            <a href="{{route('admin:expenses_items.index')}}">
                                {{__('Expenses Items')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:expenseReceipts.index'))}}">
                            <a href="{{route('admin:expenseReceipts.index')}}">
                                {{__('Expenses Receipts')}}

                            </a>
                    </ul>
                </li>

<!--
                <li class="{{setActivationClass([
route('admin:purchase-invoices.index'),
route('admin:purchase_returns.index'),
route('admin:sales.invoices.index'),
route('admin:sales.invoices.return.index'),
route('admin:quotations.index'),
route('admin:quotations.requests.index'),
route('admin:purchase-quotations.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i class="menu-icon fa fa-file-text-o"></i>
                        <span>{{__('Invoices and Quotations')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">




                        <li class="{{setActivationClass(route('admin:sales.invoices.index'))}}">
                            <a href="{{route('admin:sales.invoices.index')}}">
                                {{__('Sales Invoices')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:sales.invoices.return.index'))}}">
                            <a href="{{route('admin:sales.invoices.return.index')}}">
                                {{__('Sales Invoices Return')}}
                </a>
            </li>

            <li class="{{setActivationClass(route('admin:quotations.index'))}}">
                            <a class=" " href="{{route('admin:quotations.index')}}">
                                <span>{{__('Quotations')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:quotations.requests.index'))}}">
                            <a class=" " href="{{route('admin:quotations.requests.index')}}">

                                <span>{{__('Quotations Requests')}}</span>
                            </a>
                        </li>


                    </ul>
                </li> -->
            <li class="{{setActivationClass([
route('admin:work-cards.index'),
route('admin:maintenance.status.index.report'),
route('admin:reservations.index'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i class="menu-icon fa fa-car"></i>
                        <span>{{__('Car maintenence')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">

                    </ul>
                </li>



                <li class="{{setActivationClass([
url('admin/shifts'),
url('admin/employee_settings'),
url('admin/employees_data'),
url('admin/employees_attendance'),
url('admin/employees_delay'),
url('admin/employee_reward_discount'),
url('admin/employee-absence'),
url('admin/advances'),
url('admin/employees_salaries'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i style="color:#3C5DF2 !important"
                                                                     class="menu-icon fa fa-group"></i><span>{{__('Employees Accounts')}}</span><span
                            class="menu-arrow fa fa-angle-down"></span></a>
                    <ul class="sub-menu js__content wg-menu" style="">

                        @if(checkIfShiftActive())
                <li class="{{setActivationClass(url('admin/shifts'))}}">
                                <a href="{{url('admin/shifts')}}">{{__('Shifts')}}</a>
                            </li>
                        @endif


                <li class="{{setActivationClass(url('admin/employee_settings'))}}">
                            <a href="{{url('admin/employee_settings')}}">{{__('Employees settings')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/employees_data'))}}">
                            <a href="{{url('admin/employees_data')}}">{{__('Employees Data')}}
                </a>
            </li>

            <li class="{{setActivationClass(url('admin/employees_attendance'))}}">
                            <a href="{{url('admin/employees_attendance')}}">{{__('Employees Attendance/Departure')}}</a>
                        </li>

                         <li class="{{setActivationClass(url('admin/employees_delay'))}}">
                            <a href="{{url('admin/employees_delay')}}">{{__('Employee Delay')}}</a>
                        </li>

                         <li class="{{setActivationClass(url('admin/employee_reward_discount'))}}">
                            <a href="{{url('admin/employee_reward_discount')}}">{{__('Employee Reward/Discount')}}</a>
                        </li>

                        <li class="{{setActivationClass(url('admin/employee-absence'))}}">
                            <a href="{{url('admin/employee-absence')}}">{{__('Employee Absences')}}</a>
                        </li>

                         <li class="{{setActivationClass(url('admin/advances'))}}">
                            <a href="{{url('admin/advances')}}">{{__('Employee Advances')}}</a>
                        </li>

                         <li class="{{setActivationClass(url('admin/employees_salaries'))}}">
                            <a href="{{url('admin/employees_salaries')}}">{{__('Employee Salaries')}}</a>
                        </li>

                    </ul>
                </li>


            @include('accounting-module.custom-aside')



            <!-- <li class="{{setActivationClass([
route('admin:concession-types.index'),
route('admin:concession-relations.create'),
                     ])}}">
                    <a class="  parent-item js__control" href="#"><i class="menu-icon fa fa-mail-reply-all"></i>
                        <span>{{__('Concessions')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>

                    <ul class="sub-menu js__content">

                        <li class="{{setActivationClass(route('admin:concession-types.index'))}}">
                            <a class=" " href="{{route('admin:concession-types.index')}}">
                                <span>{{__('Concession Types')}}</span></a>
                        </li>

                        <li class="{{setActivationClass(route('admin:concession-relations.create'))}}">
                            <a class=" " href="{{route('admin:concession-relations.create')}}">
                                <span>{{__('Concession Relation')}}</span></a>
                        </li>

                    </ul>
                </li> -->

                <li class="{{setActivationClass([
url('admin/users/archive').'?archive',
url('admin/concessions/archive'),
                     ])}}">
                    <a class="parent-item js__control" href="#">
                        <i style="color:#3C5DF2 !important" class="menu-icon fa fa-archive"></i>
                        <span>{{__('System Archive')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content wg-menu" style="">
                        <li class="{{setActivationClass(url('admin/concessions/archive'))}}">
                            <a href="{{url('admin/concessions/archive')}}">{{__('Concessions Archives')}}</a>
                        </li>
                        <li class="{{setActivationClass(url('admin/activity-log/archive'))}}">
                            <a href="{{url('admin/activity-log/archive')}}">{{__('Activity-log Archives')}}</a>
                        </li>
                    </ul>

                    <ul class="sub-menu js__content wg-menu" style="">
                        <li class="{{setActivationClass(url('admin/concessions/archive'))}}">
                            <a href="{{url('admin/concessions/archive')}}">{{__('Concessions Archives')}}</a>
                        </li>
                        <li class="{{setActivationClass(url('admin/activity-log/archive'))}}">
                            <a href="{{url('admin/activity-log/archive')}}">{{__('Activity-log Archives')}}</a>
                        </li>
                    </ul>
                </li>


                <li class="{{setActivationClass([
route('admin:egyptian_federation.index'),
route('admin:commercial_register.index'),
route('admin:tax_card.index'),
route('admin:register_added_value.index'),
route('admin:company_contract.index'),
route('admin:security_approval.index'),
                     ])}}">
                    <a class="parent-item js__control" href="#">
                        <i style="color:#0E73D2 !important" class="menu-icon fa fa-folder-open-o"></i>
                        <span>{{__('Official data files')}}</span>
                        <span class="menu-arrow fa fa-angle-down"></span>
                    </a>
                    <ul class="sub-menu js__content">


                        <li class="{{setActivationClass(route('admin:egyptian_federation.index'))}}">
                            <a href="{{route('admin:egyptian_federation.index')}}">
                                {{__('Egyptian Federation')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:commercial_register.index'))}}">
                            <a href="{{route('admin:commercial_register.index')}}">
                                {{__('Commercial Register')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:tax_card.index'))}}">
                            <a href="{{route('admin:tax_card.index')}}">
                                {{__('Company Tax Card')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:register_added_value.index'))}}">
                            <a href="{{route('admin:register_added_value.index')}}">
                                {{__('Register Added Value')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:company_contract.index'))}}">
                            <a href="{{route('admin:company_contract.index')}}">
                                {{__('Company Contract')}}
                            </a>
                        </li>
                        <li class="{{setActivationClass(route('admin:security_approval.index'))}}">
                            <a href="{{route('admin:security_approval.index')}}">
                                {{__('Security Approval')}}
                            </a>
                        </li>
                    </ul>
                </li>



            </ul>
        </div>
        <!-- /.navigation -->
    </div>
    <!-- /.content -->
</div>
