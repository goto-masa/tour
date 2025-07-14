<div class="p-frame w-full sm:max-w-2xl mx-auto p-2 sm:p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-8 text-center md:text-left">下書き作成フォーム<br>
        <span class="p-title-h2">Bill Draft Create Form (Bill: {{ $bill->name }})</span>
    </h2>
    @if ($message)
        <div class="mb-8 p-2 bg-green-100 text-green-800 rounded">
            {{ $message }}
        </div>
    @endif
    @error('error')
        <div class="mb-8 p-2 bg-red-100 text-red-800 rounded">
            {{ $message }}
        </div>
    @enderror
    <form wire:submit.prevent="submit">
        <div class="mb-8">
            <label class="block mb-1 font-bold">Message-ID <span class="text-red-500">*</span></label>
            <span class="p-text text-xs text-gray-500 block mb-2">
                メールのやり取り画面右上の3点オプション（︙）から「原文を表示」をクリックし、表示された内容の中にある「メール ID」をコピーして貼り付けてください。<br>
                例: &lt;xxxxxxxxxx@mail.gmail.com&gt;
            </span>
            <input type="text" wire:model.defer="rfc822_msgid" class="w-full border rounded p-2" required placeholder="&lt;xxxxxxxxxx@mail.gmail.com&gt;" />
            @error('rfc822_msgid') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="mb-8">
            <label class="block mb-1 font-bold">本文 (HTML 可) <span class="text-red-500">*</span></label>
            <span class="p-text text-xs text-gray-500 block mb-2">下書きメールの本文を入力してください。HTMLも利用可能です。</span>
            <textarea wire:model.defer="body" rows="6" class="w-full border rounded p-2" required></textarea>
            @error('body') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">下書きを作成する</button>
        </div>
    </form>
</div>
