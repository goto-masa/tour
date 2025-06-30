<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>...</title>
    <style>
        @media screen and (max-width:768px){
            .p-frame{
                padding: 20px;
                height: 100%;
            }
        }
    </style>
</head>
<div class="p-frame w-full sm:max-w-2xl mx-auto p-2 sm:p-6 bg-white rounded shadow">
    <h2 class="text-xl font-bold mb-8  text-center md:text-left">KRO ガイド業務完了報告書<br>
    <span class="p-title-h2">KRO Guide Work Completion Report</span></h2>

    @if ($message)
        <div class="mb-8 p-2 bg-green-100 text-green-800 rounded">
            {{ $message }}
        </div>
    @endif
    <div class="mb-8">
        <label class="block mb-1"><b>メールアドレス</b><span class="text-red-500">*</span>
        </label>
        <input type="email" wire:model.defer="guide_email" class="w-full border rounded p-2" placeholder="メールアドレス"/>
        @error('guide_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Your Name</b><span class="text-red-500">*</span><br>
            <span class="p-text">
                あなたの名前を記入してください。<br>
                Please fill in your name.
            </span>
        </label>
        <input type="text" wire:model.defer="guide_name" class="w-full border rounded p-2" placeholder="回答を入力"/>
        @error('guide_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Guest Name</b><span class="text-red-500">*</span><br>
            <span class="p-text">
                旅をアレンジさせて頂いたゲスト名（代表者名）を入力ください。<br>
                Please enter the name of the guest (representative name) whose trip you have arranged.
            </span>
        </label>
        <input type="text" wire:model.defer="guest_name" class="w-full border rounded p-2" placeholder="回答を入力"/>
        @error('guest_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Number of Guests</b><span class="text-red-500">*</span><br>
            <span class="p-text">
                ゲストの方々の合計人数を入力下さい。<br>
                Please enter the total number of guests.
            </span>
        </label>
        <select wire:model.defer="number_of_guests" class="w-full border rounded p-2">
            <option value="">選択してください</option>
            @for ($i = 1; $i <= 15; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        @error('number_of_guests') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Guide Start Time</b><span class="text-red-500">*</span><br>
            <span class="p-text">
                ガイドがスタートした日時を入力してください。<br>
                Enter the date and time when the guide started.
            </span>
        </label>
        <input type="date" wire:model.defer="service_date" class="w-full border rounded p-2" />
        @error('service_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Guide Finish Time</b><span class="text-red-500">*</span><br>
            <span class="p-text">
                ガイドが終了した日時を入力してください。<br>
                Enter the date and time the guide was completed.
            </span>
        </label>
        <input type="date" wire:model.defer="finish_time" class="w-full border rounded p-2"/>
        @error('finish_time') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Duration</b><br>
            <span class="p-text">
                ガイドの合計時間を入力してください。例えば10時にスタートし、20時に終了した場合、「10」と回答ください。<br>
                Please enter the total time of the guide. For example, if the tour started at 10:00 and ended at 20:00, please answer "10".
            </span>
        </label>
        <input type="number" wire:model.defer="duration" class="w-full border rounded p-2" placeholder="回答を入力"/>
        @error('duration') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    
    <div class="mb-8">
        <label class="block mb-1"><b>Guide Schedule</b><br>
            <span class="p-text">
                訪れた場所と、そこに何時から何時までいたのか、詳細を記入してください。<br>
                Provide details of where you visited and what time you were there。<br>
                <br>
                <span class="block text-gray-500 text-xs mb-2">記入例　Example：</span>
                <span class="block text-gray-500 text-xs mb-2">1000-1200 東京タワー</span>
                <span class="block text-gray-500 text-xs mb-2">1400-1600 浅草</span>
                <span class="block text-gray-500 text-xs mb-4">1700-2000 スカイツリー</span>
            </span>
        </label>
        <div class="flex flex-col sm:flex-row gap-2 mb-2">
            <input type="text" wire:model.defer="schedule_time" placeholder="1000-1200" class="border rounded p-2 w-full sm:w-32" />
            <input type="text" wire:model.defer="schedule_item" placeholder="東京タワー" class="border rounded p-2 w-full sm:flex-1" />
            <button type="button" wire:click="addSchedule" class="bg-blue-500 text-white px-3 py-1 rounded w-full sm:w-auto">追加</button>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-2">
            <div class="font-bold text-blue-700 mb-1">入力内容</div>
            <ul>
                @foreach ($schedules as $i => $schedule)
                    <li class="flex items-center gap-2 mb-1">
                        <span class="text-blue-900">{{ $schedule['time'] }} {{ $schedule['place'] }}</span>
                        <button type="button" wire:click="removeSchedule({{ $i }})" class="text-red-500">削除</button>
                    </li>
                @endforeach
            </ul>
            @if (count($schedules) === 0)
                <div class="text-gray-400 text-xs">まだ入力がありません</div>
            @endif
        </div>
        @error('schedules') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Replacement Expenses</b><br>
            <span class="p-text">
                ゲストの代わりに建替えた費用がありましたらこちらに記入してください。ない場合はNone、0円と記入ください。<br>
                Please enter here any expenses you have reconstructed on behalf of your guests. If none, please write None or 0 yen.<br>
                <br>
                <span class="block text-gray-500 text-xs mb-2">記入例　Example：</span>
                <span class="block text-gray-500 text-xs mb-2">東京タワー入場券 ：1,000円</span>
                <span class="block text-gray-500 text-xs mb-4">Tokyo tower entrance ticket：1,000JPY</span>
            </span>
        </label>
        <div class="flex flex-col sm:flex-row gap-2 mb-2">
            <input type="text" wire:model.defer="expense_item" placeholder="東京タワー入場券" class="border rounded p-2 w-full sm:flex-1" />
            <input type="text" wire:model.defer="expense_amount" placeholder="1,000円" class="border rounded p-2 w-full sm:w-32" />
            <button type="button" wire:click="addExpense" class="bg-blue-500 text-white px-3 py-1 rounded w-full sm:w-auto">追加</button>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded p-3 mb-2">
            <div class="font-bold text-blue-700 mb-1">入力内容</div>
            <ul>
                @foreach ($expenses as $i => $expense)
                    <li class="flex items-center gap-2 mb-1">
                        <span class="text-blue-900">{{ $expense['item'] }}: {{ $expense['amount'] }}</span>
                        <button type="button" wire:click="removeExpense({{ $i }})" class="text-red-500">削除</button>
                    </li>
                @endforeach
            </ul>
            @if (count($expenses) === 0)
                <div class="text-gray-400 text-xs">まだ入力がありません</div>
            @endif
        </div>
        @error('expenses') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="mb-8">
        <label class="block mb-1"><b>Guide Report</b><br>
            <span class="p-text">
                Guide Report<br>
                ガイドされた内容を簡単にレポートしてください。その際、以下の情報を記載するようにしてください。<br>
                1. 旅程（予定通りだったか、変更があったか）<br>
                2. 顧客満足（ゲストの方々は満足されたご様子だったか、一部不満が残られている様子だったか）<br>
                3. その他申送り事項（KRO、ないしホテルに申送りする事項はあるか）<br>
                Please provide a brief report of what was guided. In doing so, please include the following information<br>
                1. the itinerary (was it as planned or were there any changes)<br>
                2. customer satisfaction (were the guests satisfied or were some dissatisfied)<br>
                3. any other matters to be forwarded (are there any matters to be forwarded to KRO or the hotel)
            </span>
        </label>
        <textarea wire:model.defer="report" class="w-full border rounded p-2" placeholder="回答を入力"></textarea>
        @error('report') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <button 
        type="button" 
        wire:click="openConfirmModal" 
        class="bg-green-600 text-white px-4 py-2 rounded hover:cursor-pointer"
    >申請内容を確認</button>

    @if ($errors->any())
        <div class="text-red-600 text-sm mt-2 mb-4">入力項目にエラーがあります</div>
    @endif

    @if ($showConfirmModal)
        <style>
            body { overflow: hidden !important; }
            @media (max-width: 640px) {
                .modal-content-custom { max-width: 98vw !important; padding: 1rem !important; }
            }
        </>
        <div class="p-modal fixed inset-0 bg-opacity-10 flex items-center justify-center z-50" wire:click="hideConfirmModal">
            <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-xl modal-content-custom relative overflow-y-auto max-h-[90vh] border border-blue-100" wire:click.stop>
                <button type="button" wire:click="hideConfirmModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-700 text-3xl leading-none font-bold transition-all duration-150" style="line-height:1;">×</button>
                <h3 class="text-2xl font-bold mb-6 text-blue-800 text-center tracking-wide">申請内容の確認</h3>
                <div class="space-y-4">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイドメール</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $guide_email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイド名</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $guide_name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ゲスト名</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $guest_name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ゲスト人数</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $number_of_guests }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイド開始日時</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $service_date }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイド終了日時</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $finish_time }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイド合計時間</span>
                        <span class="font-semibold text-lg text-gray-800">{{ $duration }}</span>
                    </div>
                    <hr class="my-2 border-blue-100">
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイドスケジュール</span>
                        <ul class="ml-2 mt-1 space-y-1">
                            @forelse ($schedules as $schedule)
                                <li class="text-gray-700">{{ $schedule['time'] }} {{ $schedule['place'] }}</li>
                            @empty
                                <li class="text-gray-400">なし</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">経費一覧</span>
                        <ul class="ml-2 mt-1 space-y-1">
                            @forelse ($expenses as $expense)
                                <li class="text-gray-700">{{ $expense['item'] }}: {{ $expense['amount'] }}</li>
                            @empty
                                <li class="text-gray-400">なし</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500 mb-1">ガイドレポート</span>
                        <span class="text-gray-700 whitespace-pre-line">{{ $report }}</span>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-8">
                    <button type="button" wire:click="hideConfirmModal" class="px-6 py-2 rounded border border-blue-200 bg-white hover:bg-blue-50 transition">戻る</button>
                    <button type="button" wire:click="submit" class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">送信</button>
                </div>
            </div>
        </div>
    @endif
</div>
