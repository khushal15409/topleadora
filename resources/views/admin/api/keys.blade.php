@extends('layouts.admin')

@section('title', 'API Keys')

@section('content')
    <!-- Page Header -->
    <div class="md:flex block items-center justify-between mb-6 mt-[2rem] page-header-breadcrumb">
        <div class="my-auto">
            <h5 class="page-title text-[1.3125rem] font-medium text-defaulttextcolor mb-0">{{ __('API Keys & Security') }}</h5>
            <nav>
                <ol class="flex items-center whitespace-nowrap min-w-0">
                    <li class="text-[12px]">
                        <a class="flex items-center text-primary hover:text-primary" href="{{ route('dashboard.api.overview') }}">
                            {{ __('API Client') }}
                            <i class="ti ti-chevrons-right flex-shrink-0 mx-3 overflow-visible text-textmuted rtl:rotate-180"></i>
                        </a>
                    </li>
                    <li class="text-[12px]">
                        <a class="flex items-center text-textmuted" href="javascript:void(0);">
                            {{ __('API Keys') }}
                        </a>
                    </li>
                </ol>
            </nav>
        </div>
        <div class="flex xl:my-auto right-content align-items-center">
            <div class="xl:mb-0">
                <a href="{{ route('dashboard.api.docs') }}" class="ti-btn ti-btn-info-full text-white !mb-0">
                    <i class="ri-book-3-line me-1"></i> View Documentation
                </a>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->

    @if(session('new_token'))
        <div class="bg-success/10 text-success border border-success/20 p-4 rounded-md mb-6 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-3 w-full">
                <div class="avatar avatar-sm rounded-full bg-success text-white">
                    <i class="ri-checkbox-circle-fill"></i>
                </div>
                <div class="flex-grow w-full pe-4">
                    <h6 class="font-bold text-[14px] mb-1">{{ __('Your New API Key is Ready!') }}</h6>
                    <p class="text-[12px] mb-3">{{ __("Please copy this key right now. For your security, it won't be shown again.") }}</p>
                    <div class="flex w-full md:w-2/3 items-center border border-success/30 rounded-md overflow-hidden shadow-sm">
                        <input type="text" class="w-full bg-white px-3 py-2 text-sm text-defaulttextcolor border-0 focus:ring-0 font-mono" value="{{ session('new_token') }}" readonly id="newTokenInput">
                        <button class="ti-btn ti-btn-success-full !mb-0 !rounded-none !border-0 text-white font-medium px-4 py-2" type="button" onclick="copyToken()" id="copyBtn">
                            <i class="ri-clipboard-line me-1"></i> Copy
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-success/10 text-success border border-success/20 p-3 rounded-md mb-6 flex justify-between items-center" role="alert">
            <div class="flex items-center gap-2">
                <i class="ri-check-double-line text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="text-success hover:text-success/80" data-bs-dismiss="alert" aria-label="Close">
                <i class="ri-close-line"></i>
            </button>
        </div>
    @endif

    <div class="grid grid-cols-12 gap-x-6">
        <!-- Generate Key Section -->
        <div class="xl:col-span-4 col-span-12">
            <div class="box shadow-none">
                <div class="box-header !border-b !border-defaultborder/10">
                    <h4 class="box-title font-semibold">{{ __('Generate New Key') }}</h4>
                </div>
                <div class="box-body">
                    <p class="text-[0.75rem] text-textmuted font-normal mb-4">{{ __('Create a new API key to authenticate your application\'s requests. We recommend creating separate keys for different environments.') }}</p>
                    <form action="{{ route('dashboard.api.keys.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-[11px] font-bold text-textmuted uppercase tracking-wider" for="name">{{ __('Key Identifier') }}</label>
                            <input type="text" class="form-control bg-light border-0 shadow-none text-sm" id="name" name="name" placeholder="e.g. Production Server" required>
                        </div>
                        <button type="submit" class="ti-btn ti-btn-primary-full w-full">
                            <i class="ri-add-line me-1"></i> {{ __('Generate API Key') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Active Keys Section -->
        <div class="xl:col-span-8 col-span-12">
            <div class="box shadow-none overflow-hidden">
                <div class="box-header !border-b !border-defaultborder/10 flex justify-between items-center">
                    <div>
                        <h4 class="box-title font-semibold">{{ __('Active API Keys') }}</h4>
                        <p class="text-textmuted text-[0.7rem] mt-1 mb-0">{{ __('Manage and revoke your existing access tokens.') }}</p>
                    </div>
                    <span class="badge bg-light text-textmuted border border-defaultborder">{{ count($tokens) }} Active</span>
                </div>
                <div class="box-body !p-0">
                    <div class="table-responsive">
                        <table class="ti-custom-table table-hover text-nowrap w-full">
                            <thead class="bg-gray-100/50 dark:bg-black/20">
                                <tr>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Key Name') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Token Identifier') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Last Used') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider">{{ __('Created Date') }}</th>
                                    <th scope="col" class="!py-3 !px-4 text-[11px] font-bold uppercase tracking-wider text-end">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tokens as $token)
                                    <tr class="border-b last:border-0 hover:bg-gray-50/50 transition-colors h-14">
                                        <td class="!px-4">
                                            <div class="flex items-center">
                                                <div class="me-2 avatar avatar-xs rounded-full bg-primary/10 text-primary">
                                                    <i class="ri-shield-keyhole-line text-[12px]"></i>
                                                </div>
                                                <span class="font-bold text-sm">{{ $token->name }}</span>
                                            </div>
                                        </td>
                                        <td class="!px-4">
                                            <div class="font-mono text-textmuted bg-gray-100 px-2 py-1 rounded text-[11px] border border-gray-200 inline-block">
                                                ******************
                                            </div>
                                        </td>
                                        <td class="!px-4 text-[12px]">
                                            @if($token->last_used_at)
                                                <span class="text-defaulttextcolor font-medium"><i class="ri-time-line me-1 text-textmuted"></i> {{ $token->last_used_at->diffForHumans() }}</span>
                                            @else
                                                <span class="badge bg-light text-textmuted border border-defaultborder font-normal">{{ __('Never Used') }}</span>
                                            @endif
                                        </td>
                                        <td class="!px-4 text-textmuted text-[12px]">{{ $token->created_at->format('M d, Y') }}</td>
                                        <td class="text-end !px-4">
                                            <form action="{{ route('dashboard.api.keys.destroy', $token->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to revoke this key? Any application using this key will immediately lose access. This cannot be undone.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ti-btn ti-btn-danger-full !py-1 !px-2 !text-[11px]" title="Revoke Key">
                                                    <i class="ri-delete-bin-line me-1"></i> Revoke
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-textmuted py-12">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="avatar avatar-lg bg-light text-textmuted rounded-full mb-3 shadow-none border">
                                                    <i class="ri-key-off-line text-2xl"></i>
                                                </div>
                                                <h6 class="font-bold text-[14px] mb-1">{{ __('No API keys found') }}</h6>
                                                <p class="text-[12px] mb-0">{{ __('Generate a new key from the left panel to get started.') }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyToken() {
        var copyText = document.getElementById("newTokenInput");
        copyText.select();
        copyText.setSelectionRange(0, 99999);
        navigator.clipboard.writeText(copyText.value);
        
        let copyBtn = document.getElementById("copyBtn");
        let originalContent = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="ri-check-line me-1"></i> Copied!';
        copyBtn.classList.replace('ti-btn-success-full', 'ti-btn-primary-full');
        
        setTimeout(() => {
            copyBtn.innerHTML = originalContent;
            copyBtn.classList.replace('ti-btn-primary-full', 'ti-btn-success-full');
        }, 2500);
    }
</script>
@endpush