<x-filament-panels::page>
    <div x-data="{
        search: '',
        items: @js($items),
        page: 1,
        perPage: 100,
        sortBy: 'vnum',
        sortAsc: true,
        filterType: '',
        filterSubtype: '',
        get types() {
            return [...new Set(this.items.map(i => i.type))].sort();
        },
        get subtypes() {
            if (!this.filterType) return [];
            return [...new Set(this.items.filter(i => i.type === this.filterType).map(i => i.subtype).filter(Boolean))].sort();
        },
        get filtered() {
            let result = this.items;
            if (this.search) {
                const q = this.search.toLowerCase();
                result = result.filter(i => i.name.toLowerCase().includes(q) || String(i.vnum).includes(q));
            }
            if (this.filterType) {
                result = result.filter(i => i.type === this.filterType);
            }
            if (this.filterSubtype) {
                result = result.filter(i => i.subtype === this.filterSubtype);
            }
            const key = this.sortBy;
            const asc = this.sortAsc;
            return [...result].sort((a, b) => {
                let va = a[key], vb = b[key];
                if (typeof va === 'string') { va = va.toLowerCase(); vb = vb.toLowerCase(); }
                if (va < vb) return asc ? -1 : 1;
                if (va > vb) return asc ? 1 : -1;
                return 0;
            });
        },
        get paginated() {
            return this.filtered.slice(0, this.page * this.perPage);
        },
        get hasMore() {
            return this.paginated.length < this.filtered.length;
        },
        loadMore() { this.page++; },
        resetPage() { this.page = 1; },
        toggleSort(col) {
            if (this.sortBy === col) { this.sortAsc = !this.sortAsc; }
            else { this.sortBy = col; this.sortAsc = true; }
            this.page = 1;
        },
        clearFilters() {
            this.search = '';
            this.filterType = '';
            this.filterSubtype = '';
            this.page = 1;
        }
    }">
        {{-- Filters --}}
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
            {{-- Search --}}
            <div style="position:relative; width:100%; max-width:300px;">
                <svg style="position:absolute; left:12px; top:50%; transform:translateY(-50%); width:16px; height:16px; color:#9ca3af;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input x-model.debounce.150ms="search" @input="resetPage()" type="text"
                    placeholder="Search name or VNUM..."
                    style="width:100%; padding:9px 14px 9px 36px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#111; outline:none; box-shadow:0 1px 2px rgba(0,0,0,0.05);"
                    onfocus="this.style.borderColor='#f97316'; this.style.boxShadow='0 0 0 2px rgba(249,115,22,0.2)'"
                    onblur="this.style.borderColor='#d1d5db'; this.style.boxShadow='0 1px 2px rgba(0,0,0,0.05)'" />
            </div>

            {{-- Type filter --}}
            <select x-model="filterType" @change="filterSubtype = ''; resetPage()"
                style="padding:9px 12px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#111; outline:none; cursor:pointer; min-width:160px;">
                <option value="">All Types</option>
                <template x-for="t in types" :key="t">
                    <option :value="t" x-text="t"></option>
                </template>
            </select>

            {{-- Subtype filter --}}
            <select x-model="filterSubtype" @change="resetPage()"
                x-show="subtypes.length > 0"
                x-transition
                style="padding:9px 12px; font-size:13px; border:1px solid #d1d5db; border-radius:8px; background:#fff; color:#111; outline:none; cursor:pointer; min-width:160px;">
                <option value="">All Subtypes</option>
                <template x-for="s in subtypes" :key="s">
                    <option :value="s" x-text="s"></option>
                </template>
            </select>

            {{-- Clear filters --}}
            <button x-show="search || filterType || filterSubtype" @click="clearFilters()" type="button"
                style="padding:9px 14px; font-size:13px; font-weight:500; color:#6b7280; background:#f3f4f6; border:1px solid #d1d5db; border-radius:8px; cursor:pointer;"
                onmouseover="this.style.background='#e5e7eb'"
                onmouseout="this.style.background='#f3f4f6'">
                Clear filters
            </button>

            {{-- Count --}}
            <span style="font-size:13px; color:#6b7280; white-space:nowrap; margin-left:auto;">
                Showing <strong x-text="paginated.length" style="color:#111"></strong> of <strong x-text="filtered.length" style="color:#111"></strong> items
            </span>
        </div>

        {{-- Table --}}
        <div style="border-radius:12px; overflow:hidden; border:1px solid #e5e7eb; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.06);">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <thead>
                    <tr style="background:#f9fafb; border-bottom:2px solid #e5e7eb;">
                        <th @click="toggleSort('vnum')" style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; cursor:pointer; user-select:none; width:100px;">
                            VNUM
                            <span x-show="sortBy === 'vnum'" x-text="sortAsc ? '▲' : '▼'" style="font-size:10px; margin-left:4px;"></span>
                        </th>
                        <th @click="toggleSort('name')" style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; cursor:pointer; user-select:none;">
                            Name
                            <span x-show="sortBy === 'name'" x-text="sortAsc ? '▲' : '▼'" style="font-size:10px; margin-left:4px;"></span>
                        </th>
                        <th @click="toggleSort('type')" style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; cursor:pointer; user-select:none; width:140px;">
                            Type
                            <span x-show="sortBy === 'type'" x-text="sortAsc ? '▲' : '▼'" style="font-size:10px; margin-left:4px;"></span>
                        </th>
                        <th @click="toggleSort('subtype')" style="padding:12px 16px; text-align:left; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; cursor:pointer; user-select:none; width:140px;">
                            Subtype
                            <span x-show="sortBy === 'subtype'" x-text="sortAsc ? '▲' : '▼'" style="font-size:10px; margin-left:4px;"></span>
                        </th>
                        <th style="padding:12px 16px; text-align:right; font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:#6b7280; width:70px;">Icon</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="item in paginated" :key="item.vnum">
                        <tr style="border-bottom:1px solid #f3f4f6; transition:background 0.1s;"
                            onmouseover="this.style.background='#f9fafb'"
                            onmouseout="this.style.background='transparent'">
                            <td style="padding:8px 16px; white-space:nowrap;">
                                <span style="display:inline-block; font-family:ui-monospace,monospace; font-size:13px; font-weight:500; color:#374151; background:#f3f4f6; padding:2px 8px; border-radius:6px; border:1px solid #e5e7eb;" x-text="item.vnum"></span>
                            </td>
                            <td style="padding:8px 16px; font-size:14px; font-weight:500; color:#111827;" x-text="item.name"></td>
                            <td style="padding:8px 16px;">
                                <span style="display:inline-block; font-size:12px; font-weight:500; color:#4b5563; background:#eef2ff; padding:2px 8px; border-radius:6px;" x-text="item.type"></span>
                            </td>
                            <td style="padding:8px 16px;">
                                <span x-show="item.subtype" style="display:inline-block; font-size:12px; font-weight:500; color:#6b7280; background:#f3f4f6; padding:2px 8px; border-radius:6px;" x-text="item.subtype"></span>
                            </td>
                            <td style="padding:8px 16px; text-align:right;">
                                <img x-show="item.icon" :src="item.icon"
                                     style="width:32px; height:32px; object-fit:contain; display:inline-block;"
                                     onerror="this.style.visibility='hidden'" />
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Load more --}}
        <div x-show="hasMore" style="text-align:center; padding-top:16px;">
            <button @click="loadMore()" type="button"
                style="display:inline-flex; align-items:center; gap:6px; padding:8px 20px; font-size:13px; font-weight:600; color:#374151; background:#fff; border:1px solid #d1d5db; border-radius:8px; cursor:pointer; box-shadow:0 1px 2px rgba(0,0,0,0.05); transition:background 0.15s;"
                onmouseover="this.style.background='#f9fafb'"
                onmouseout="this.style.background='#fff'">
                <svg style="width:16px; height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
                Load more items
            </button>
        </div>

        {{-- Empty --}}
        <div x-show="filtered.length === 0" style="text-align:center; padding:48px 0; color:#9ca3af;">
            <svg style="width:40px; height:40px; margin:0 auto 8px; opacity:0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <p style="font-size:14px;">No items match your search.</p>
        </div>
    </div>
</x-filament-panels::page>
