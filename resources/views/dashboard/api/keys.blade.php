@extends('layouts.admin')

@section('title', 'API Keys Management')

@section('content')
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('API Keys & Security') }}
            </h5>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        <div class="xl:col-span-8 col-span-12">
            <div class="box">
                <div class="box-header flex justify-between items-center">
                    <h4 class="box-title">{{ __('Your Active Tokens') }}</h4>
                    <button type="button" class="ti-btn ti-btn-primary" data-bs-toggle="modal"
                        data-bs-target="#generateKeyModal">
                        {{ __('Generate New Key') }}
                    </button>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table text-nowrap">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Last Used') }}</th>
                                    <th>{{ __('Created At') }}</th>
                                    <th class="text-end">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tokens as $token)
                                    <tr>
                                        <td class="font-semibold">{{ $token->name }}</td>
                                        <td>{{ $token->last_used_at ? $token->last_used_at->diffForHumans() : __('Never') }}
                                        </td>
                                        <td>{{ $token->created_at->format('d M Y') }}</td>
                                        <td class="text-end">
                                            <form action="{{ route('dashboard.api.keys.destroy', $token->id) }}" method="POST"
                                                data-confirm="{{ __('Revoke this key? It will stop working immediately.') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger ti-btn-sm">
                                                    <i class="ri-delete-bin-line"></i> {{ __('Revoke') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-textmuted">
                                            {{ __('No API keys found. Generate one to start using our services.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="xl:col-span-4 col-span-12">
            <div class="box">
                <div class="box-header">
                    <h4 class="box-title">{{ __('Developer Quickstart') }}</h4>
                </div>
                <div class="box-body">
                    <p class="text-[12px] text-textmuted mb-4">
                        {{ __('Use the following base URL and your bearer token for requests.') }}</p>

                    <div class="mb-3">
                        <label class="form-label text-[11px] font-bold text-uppercase">{{ __('Base URL') }}</label>
                        <div class="flex items-center gap-2 p-2 bg-light rounded text-[12px] font-mono">
                            {{ url('/api') }}
                        </div>
                    </div>

                    <div class="p-3 bg-primary/10 border border-primary/20 rounded">
                        <h6 class="text-primary font-bold mb-2"><i class="ri-information-line"></i>
                            {{ __('Security Warning') }}</h6>
                        <p class="text-[11px] mb-0">
                            {{ __('Never share your API keys or commit them to public repositories. Keys should be stored securely in environment variables.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- New Token Alert --}}
    @if(session('new_token'))
        <div class="modal fade show" id="newTokenModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title">{{ __('New API Key Generated') }}</h5>
                    </div>
                    <div class="modal-body text-center py-5">
                        <p class="mb-4">{{ __('Please copy this key now. For your security, it will NOT be shown again.') }}</p>
                        <div
                            class="p-3 bg-light rounded font-mono text-break mb-3 text-[14px] border-2 border-dashed border-success">
                            {{ session('new_token') }}
                        </div>
                        <button type="button" class="ti-btn ti-btn-success w-full"
                            onclick="document.getElementById('newTokenModal').style.display='none'">
                            {{ __('I have saved this key') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Generate Modal --}}
    <div class="modal fade" id="generateKeyModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('dashboard.api.keys.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Generate New API Token') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-0">
                            <label class="form-label">{{ __('Token Name') }}</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. My Website App" required>
                            <small class="text-textmuted">{{ __('Give it a friendly name to identify it later.') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="ti-btn ti-btn-light"
                            data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="ti-btn ti-btn-primary">{{ __('Create Token') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection