@extends('layouts.app')
@section('title')
    {{ "APM | КСТ | Добавление карты проекта" }}
@endsection
@section('content')
    <div class="container">
        <h1 class="pb-5">Добавить карту проекта</h1>
        <form action="{{ route('project-store') }}" method="post" id="addMap">
            @csrf
            <div class="d-grid gap-5 addMap__grid">
                {{-- Общая информация --}}
                <div class="project-add alert">
                    <h2>I Общая информация по проекту</h2>

                    <div class="form-group mb-3">
                        <label for="projNum">Номер проекта по реестру:</label>
                        <div class="d-flex gap-3 mb-2">
                            <input type="hidden" class="form-control" name="projNumPre" id="projNumPre" value="{{ $currentYear }}" readonly>
                            <div>
                                @if($user->role === 'admin' || $user->role === 'responsible')
                                    <input list="projNumbs" name="projNumSuf" required placeholder="Выберите тип" id="projNumSuf" class="form-control" />
                                    <datalist id="projNumbs">
                                        <option value="Группа 1" data-group="1">
                                        <option value="Группа 2" data-group="2">
                                        <option value="Группа 3" data-group="3">
                                        <option value="Группа 4" data-group="4">
                                    </datalist>
                                @else
                                    <input list="projNumbs" name="projNumSuf" required placeholder="Выберите тип" id="projNumSuf" class="form-control" value="{{ $currentUserGroupNum }}" readonly/>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="projNum" id="projNumCombined">
                        <button class="btn btn-secondary" type="button" id="manualNumBtn">Ввести номер вручную</button>
                        <input type="text" name="manualProjNum" id="manualProjNum" class="form-control d-none">
                    </div>


                    <div class="form-group mb-3">
                        <label for="projManager">Руководитель проекта:</label>
                        @if($user->role === 'admin' || $user->role === 'responsible')
                        <select class="form-control" name="projManager" id="projManager" required>
                            @foreach ($projectManagers as $manager)
                                <option value="{{ $manager->fio }}" data-group="{{ $manager->groupNum }}">
                                    {{ $manager->fio }}</option>
                            @endforeach
                        </select>
                        @else
                            <input class="form-control w-100" name="projManager" id="projManager"  data-group="{{ $user->group_num}}" value="{{ $user->name }}" readonly/>
                        @endif
                    </div>
                    <div class="form-group mb-3">
                        <label for="objectName">Головная компания:</label>
                        <input type="text" class="form-control" name="proj_note" id="proj_note"
                            placeholder="Введите хэштэг проекта">
                    </div>
                    <div class="form-group mb-3">
                        <label for="objectName">Хэштэг:</label>
                        <input type="text" class="form-control" name="objectName" id="objectName"
                               placeholder="Введите наименование объекта" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="endCustomer">Конечный заказчик:</label>
                        <input type="text" class="form-control" name="endCustomer" id="endCustomer"
                            placeholder="Введите конечного заказчика">
                    </div>
                    <div class="form-group mb-3">
                        <label for="contractor">Контрагент:</label>
                        <input type="text" class="form-control" name="contractor" id="contractor"
                            placeholder="Введите контрагента">
                    </div>
                    <div class="form-group mb-3">
                        <label for="date_application">Дата поступления заявки:</label>
                        <input type="date" class="form-control" name="date_application" id="date_application"
                            placeholder="Выберите дату поступления заявки">
                    </div>
                    {{-- <div class="form-group mb-3">
                        <label for="date_offer">Дата подачи предложения:</label>
                        <input type="date" class="form-control" name="date_offer" id="date_offer"
                            placeholder="Выберите дату подачи предложения">
                    </div> --}}
                    <h3 class="mt-4 mb-3">Виды работ</h3>
                    <div class="d-flex gap-5">
                        <div class="d-flex flex-column">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="delivery" id="delivery">
                                <label class="form-check-label" for="delivery">Поставка</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pir" id="pir">
                                <label class="form-check-label" for="pir">ПИР</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="kd" id="kd">
                                <label class="form-check-label" for="kd">КД</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="production" id="production">
                                <label class="form-check-label" for="production">Производство</label>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="smr" id="smr">
                                <label class="form-check-label" for="smr">ШМР</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="pnr" id="pnr">
                                <label class="form-check-label" for="pnr">ПНР</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="po" id="po">
                                <label class="form-check-label" for="po">ПО</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="cmr" id="cmr">
                                <label class="form-check-label" for="cmr">СМР</label>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Контакт лист --}}
                <div class="contacts-add alert pt-3">
                    <h4 class="text-center">Контакт лист</h4>
                    <div id="contacts-inputs">
                        <div class="mb-3">
                            <div class="form-group mb-3">
                                <label for="fio">ФИО:</label>
                                <input type="fio" class="form-control" name="contacts[0][fio]" id="fio"
                                    placeholder="Введите ФИО" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="post">Должность:</label>
                                <input type="text" class="form-control" name="contacts[0][post]" id="post"
                                    placeholder="Введите должность">
                            </div>
                            <div class="form-group mb-3">
                                <label for="organization">Организация:</label>
                                <input type="text" class="form-control" name="contacts[0][organization]"
                                    id="organization" placeholder="Введите организацию" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="responsibility">Зона ответственности:</label>
                                <input type="text" class="form-control" name="contacts[0][responsibility]"
                                    id="responsibility" placeholder="Введите зону ответственности" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="phone">Телефон:</label>
                                <input type="text" class="form-control" name="contacts[0][phone]" id="phone"
                                    placeholder="Введите телефон">
                            </div>
                            <div class="form-group mb-3">
                                <label for="email">эл. почта:</label>
                                <input type="text" class="form-control" name="contacts[0][email]" id="email"
                                    placeholder="Введите эл.почту">
                            </div>
                        </div>
                    </div>
                    <button id="addMore-contacts" data-target="contacts"
                        class="btn btn-secondary addMore-button">Добавить
                        еще контакт</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Создать</button>
            <a href="/project-maps/all" type="button" class="btn btn-secondary mt-4" id="cancelButton">Отмена</a>
        </form>
    </div>

    <script>
        // добавление доп строк
        $(document).ready(function() {
            // индесы для каждого из разделов
            let indices = {
                contacts: 1,
            };
            /* при нажатии на кнопки определяем какой у нас target и взависимости от него добавляет HTML,
               возвращенный функцией getHtml, в соответствующую секцию */
            $(".addMore-button").click(function(event) {
                event.preventDefault();
                const target = $(this).data("target");

                $(`#${target}-inputs`).append(getHtml(target, indices[target]));
                indices[target]++;
            });
        });
        // функция возвращающая html в секцию
        function getHtml(target, index) {
            let removeButton =
                `<button class="btn btn-danger remove-btn" data-index="${index}" data-target="${target}">Удалить</button>`;
            switch (target) {
                case 'contacts':
                    return `<div class="mb-3 block" data-target="${target}" data-index="${index}">---
                        <div class="form-group mb-3">
                            <label for="fio">ФИО:</label>
                            <input type="fio" class="form-control" name="contacts[${index}][fio]" id="fio"
                                placeholder="Введите ФИО">
                        </div>
                        <div class="form-group mb-3">
                            <label for="post">Должность:</label>
                            <input type="text" class="form-control" name="contacts[${index}][post]" id="post"
                                placeholder="Введите должность">
                        </div>
                        <div class="form-group mb-3">
                            <label for="organization">Организация:</label>
                            <input type="text" class="form-control" name="contacts[${index}][organization]" id="organization"
                                placeholder="Введите организацию">
                        </div>
                        <div class="form-group mb-3">
                            <label for="responsibility">Зона ответственности:</label>
                            <input type="text" class="form-control" name="contacts[${index}][responsibility]" id="responsibility"
                                placeholder="Введите зону ответственности">
                        </div>
                        <div class="form-group mb-3">
                            <label for="phone">Телефон:</label>
                            <input type="text" class="form-control" name="contacts[${index}][phone]" id="phone"
                                placeholder="Введите телефон">
                        </div>
                        <div class="form-group mb-3">
                            <label for="email">Эл. почта:</label>
                            <input type="text" class="form-control" name="contacts[${index}][email]" id="email"
                                placeholder="Введите эл.почту">
                        </div>
                        ${removeButton}
                    </div>`
            }
        }
        $(document).on('click', '.remove-btn', function(e) {
            e.preventDefault();
            let target = $(this).data('target');
            let index = $(this).data('index');
            $(`[data-target=${target}][data-index=${index}]`).remove();
        });


        jQuery(document).ready(function($) {
            var currentUserGroup = "{{ Auth::user()->group_num }}";

            // Фильтрация групп в зависимости от роли пользователя
            $('#projNumbs option').each(function() {
                var optionGroup = $(this).data('group');
                if (optionGroup !== currentUserGroup) {
                    $(this).hide();
                }
            });

            // Выбор менеджера проекта
            var currentUser = "{{ Auth::user()->name }}";
            $('#projManager option').each(function() {
                var managerName = $(this).val();
                if (managerName === currentUser) {
                    $(this).prop('selected', true);
                }
            });

            // Обновление списка руководителей и номера проекта при изменении группы
            $('#projNumSuf').change(function() {
                var selectedGroup = $('datalist#projNumbs option[value="' + $(this).val() + '"]').data('group');

                $('#projManager').empty(); // Очистка текущего списка руководителей

                if (selectedGroup !== undefined) {
                    $.ajax({
                        url: '/get-managers/' + selectedGroup,
                        method: 'GET',
                        success: function(data) {
                            data.forEach(function(manager) {
                                $('#projManager').append($('<option>', {
                                    value: manager.fio,
                                    text: manager.fio
                                }));
                            });
                        },
                        error: function() {
                            console.error('Ошибка при получении данных с сервера');
                        }
                    });
                } else {
                    // Если группа не определена, заполнить список руководителей начальными данными
                    @foreach ($projectManagers as $manager)
                    $('#projManager').append($('<option>', {
                        value: "{{ $manager->fio }}",
                        text: "{{ $manager->fio }}"
                    }));
                    @endforeach
                }

                // Формирование номера проекта
                var projNumPre = $('#projNumPre').val();
                var projNumSuf = $(this).val();
                var combinedValue = projNumPre + '-' + projNumSuf;
                $('#projNumCombined').val(combinedValue);
            });

            // Показ/скрытие поля для ввода номера вручную
            $('#manualNumBtn').on('click', function() {
                $('#manualProjNum').toggleClass('d-none');
            });

            // Обработка формы и создание комбинированного значения
            $('#addMap').on('submit', function() {
                var manualProjNum = $('#manualProjNum').val();
                if (manualProjNum) {
                    $('#projNumCombined').val(manualProjNum);
                } else {
                    var projNumPre = $('#projNumPre').val();
                    var projNumSuf = $('#projNumSuf').val();
                    var combinedValue = projNumPre + '-' + projNumSuf;
                    $('#projNumCombined').val(combinedValue);
                }
            });
        });

    </script>
@endsection
