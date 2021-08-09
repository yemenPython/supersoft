<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupplierContactRequest;
use App\Models\Supplier;
use App\Models\SupplierContact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class SupplierContactController extends Controller
{
    public function index(Supplier $supplier, Request $request)
    {
        $contacts = $supplier->contacts;
        $contacts = $this->filter($request, $contacts);
        if ($request->isDataTable) {
            return $this->dataTableColumns($contacts);
        } else {
            return view('admin.suppliers.contacts.index', [
                'contacts' => $contacts,
                'supplier' => $supplier,
                'js_columns' => SupplierContact::getJsDataTablesColumns(),
            ]);
        }
    }

    public function store(SupplierContactRequest $request): RedirectResponse
    {
        if ($request->filled('supplier_contact_id')) {
            if ($contact = SupplierContact::find($request->supplier_contact_id)) {
                $contact->update($request->all());
                return redirect()->back()->with(['message' => __('words.supplier-contact-updated'), 'alert-type' => 'success']);
            }
            return redirect()->back()->with(['message' => __('words.something-wrong'), 'alert-type' => 'error']);

        } else {
            SupplierContact::create($request->all());
            return redirect()->back()->with(['message' => __('words.supplier-contact-created'), 'alert-type' => 'success']);
        }
    }

    public function destroy(SupplierContact $supplierContact): RedirectResponse
    {
        $supplierContact->delete();
        return redirect()->back()->with(['message' => __('words.supplier-contact-deleted'), 'alert-type' => 'success']);
    }

    public function deleteSelected(Request $request): RedirectResponse
    {
        if (isset($request->ids)) {
            SupplierContact::whereIn('id', $request->ids)->delete();
            return redirect()->back()
                ->with(['message' => __('words.selected-row-deleted'), 'alert-type' => 'success']);
        }
    }


    private function filter(Request $request, Collection $contacts): Collection
    {
        if ($request->filled('supplier_contact')) {
            $contacts =$contacts->where('id', $request->supplier_contact);
        }
        if ($request->filled('supplier_contact')) {
            $contacts =$contacts->where('id', $request->supplier_contact);
        }
        if ($request->filled('phoneNumber')) {
            $contacts =$contacts->where('id', $request->phoneNumber);
        }

        if ($request->has('end') && $request['end'] != '') {
            $contacts = $contacts->where('end', $request->end_date);
        }
        if ($request->has('active') && $request['active'] != '') {
            $contacts = $contacts->where('status', '1');
        }
        if ($request->has('inactive') && $request['inactive'] != '') {
            $contacts = $contacts->where('status', '0');
        }
        return $contacts;
    }

    /**
     * @param Collection $contacts
     * @return mixed
     * @throws \Throwable
     */
    private function dataTableColumns(Collection $contacts)
    {
        return DataTables::of($contacts)->addIndexColumn()
            ->addColumn('status', function ($contact) {
                $withStatus = true;
                return view('admin.suppliers.contacts.datatables-options', compact('contact', 'withStatus'))->render();
            })
            ->addColumn('name', function ($contact) {
                return $contact->name;
            })
            ->addColumn('job_title', function ($contact) {
                return $contact->job_title;
            })
            ->addColumn('phone1', function ($contact) {
                return $contact->phone_1;
            })
            ->addColumn('phone2', function ($contact) {
                return $contact->phone_2;
            })
            ->addColumn('address', function ($contact) {
                return $contact->address;
            })
            ->addColumn('email', function ($contact) {
                return $contact->email;
            })
            ->addColumn('start_date', function ($contact) {
                $withStartData = true;
                return view('admin.suppliers.contacts.datatables-options', compact('contact', 'withStartData'))->render();
            })
            ->addColumn('end_date', function ($contact) {
                $withEndData = true;
                return view('admin.suppliers.contacts.datatables-options', compact('contact', 'withEndData'))->render();
            })
            ->addColumn('action', function ($contact) {
                $withActions = true;
                return view('admin.suppliers.contacts.datatables-options', compact('contact', 'withActions'))->render();
            })->addColumn('options', function ($contact) {
                $withOptions = true;
                return view('admin.suppliers.contacts.datatables-options', compact('contact', 'withOptions'))->render();
            })->rawColumns(['action'])->rawColumns(['actions'])->escapeColumns([])->make(true);
    }
}
