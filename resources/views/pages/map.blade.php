@extends('layouts.app')

@section('content')
    <script type="text/javascript">
        // Функция ymaps.ready() будет вызвана, когда
        // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
        var myMap;
        var pointCollection;
        ymaps.ready(init);
        function init(){
            var location = ymaps.geolocation;
            // Создание карты.
            pointCollection = new ymaps.GeoObjectCollection();
            myMap = new ymaps.Map("map", {
                // Координаты центра карты.
                // Порядок по умолчанию: «широта, долгота».
                // Чтобы не определять координаты центра карты вручную,
                // воспользуйтесь инструментом Определение координат.
                center: [55.76, 37.64],
                // Уровень масштабирования. Допустимые значения:
                // от 0 (весь мир) до 19.
                zoom: 15
            },{
                searchControlProvider: 'yandex#search'
            });

            // Получение местоположения и автоматическое отображение его на карте.
            location.get({
                mapStateAutoApply: true
            })
                .then(
                    function(result) {
                        // Получение местоположения пользователя.
                        var userAddress = result.geoObjects.get(0).properties.get('text');
                        var userCoodinates = result.geoObjects.get(0).geometry.getCoordinates();
                        // Пропишем полученный адрес в балуне.
                        result.geoObjects.get(0).properties.set({
                            balloonContentBody: 'Адрес: ' + userAddress +
                                '<br/>Координаты:' + userCoodinates
                        });
                        myMap.geoObjects.add(result.geoObjects)
                    },
                    function(err) {
                        console.log('Ошибка: ' + err)
                    }
                );

            function userBalloon(points){
                points.forEach(point=>{

                       let newPoint =  new ymaps.Placemark([point.longitude, point.latitude], {
                        balloonContent: `${point.name}<br>Широта: ${point.latitude}<br>Долгота: ${point.longitude}`
                    },
                           {
                        preset: 'islands#icon',
                        iconColor: '#0095b6'
                    })
                    newPoint.properties.set('id', point.id);
                    pointCollection.add(newPoint)
                })
            }
            myMap.geoObjects.add(pointCollection);
            fetch("{{route('get_points')}}")
                .then((response) => response.json())
                .then((json) => userBalloon(json.points))
        }

        function errorAlert(id,text) {
             document.getElementById(id).style.display='block';
             document.getElementById(id).innerText=text;
            setTimeout(function(){
                document.getElementById(id).style.display='none';
            }, 4000);
            return false;
        }
    </script>

   <h4 id="error-alert-main" style="color: red"></h4>
<div class="row mt-5">
    <ul class="list-group col-lg-5 col-sm-12" id="points">
        <li class="list-group-item" id="li-form">
            <form>
{{--                @csrf--}}

                <div class="mb-3">
                    <label for="latitude" class="form-label">Latitude</label>
                    <input type="text" class="form-control" id="latitude" placeholder="55.76" name="latitude" required>
                </div>
                <div class="mb-3">
                    <label for="longitude" class="form-label">Longitude</label>
                    <input type="text" class="form-control" id="longitude" placeholder="37.64" name="longitude" required>
                </div>
                <div class="mb-3">
                    <label for="Name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Name of point" name="name" required>
                </div>
                <button id="add-point" class="btn btn-outline-success">Add</button>
            </form>
        </li>

        @foreach($points as $point)
        <li class="list-group-item">
            <ul class="list-group">
                <li class="list-group-item" style="cursor: pointer;" id="name-field" data-latitude="{{$point->latitude}}" data-longitude="{{$point->longitude}}" onclick="setCenter(this)">{{$point->name}}</li>
                <li class="list-group-item">{{$point->latitude}}</li>
                <li class="list-group-item">{{$point->longitude}}</li>
            </ul>

                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-latitude="{{$point->latitude}}" data-longitude="{{$point->longitude}}"
                        data-name="{{$point->name}}" data-id="{{$point->id}}" onclick="editPoint(this)"
                >Edit</button>
                <button class="btn btn-outline-danger"  onclick="deletePoint(this)" data-id="{{$point->id}}">Delete</button>

        </li>
        @endforeach
    </ul>

    <div id="map" class="col-lg-7 col-sm-12" style="height: 400px"></div>
</div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 id="error-alert-edit" style="color: red"></h4>
                    <div class="mb-3">
                        <label for="latitude" class="form-label">Latitude</label>
                        <input type="text" class="form-control" id="latitude" placeholder="55.76" name="latitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="longitude" class="form-label">Longitude</label>
                        <input type="text" class="form-control" id="longitude" placeholder="37.64" name="longitude" required>
                    </div>
                    <div class="mb-3">
                        <label for="Name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Name of point" name="name" required>
                    </div>
                    <input type="text" hidden id="id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="save-changes">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->

    <script>
        let latitude =  document.getElementById('latitude');
        let longitude =  document.getElementById('longitude');
        let name =  document.getElementById('name');
        // let _token =  document.getElementById('token');
        let btn = document.querySelector('#add-point');
        btn.addEventListener('click',addPoint);
        async function addPoint(e){
            e.preventDefault();
            let obj = { name:name.value, latitude:latitude.value, longitude:longitude.value };
            const res = await fetch('{{route('add_point')}}', {
                method:'POST',
                mode: 'cors',
                headers:{
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Content-Type': 'application/json'
                },
                body:JSON.stringify(obj)
            });
            const data = await res.json()
            if(res.status===422){
                // console.log(data)
                errorAlert("error-alert-main","Input data is invalid")
                console.log("Error "+ res.status )
            }
            else if(res.ok){
                clearInput()
                renderMapPoint(obj,data.id)
                renderPointLi(obj,data.id)
                // console.log(data)

            }
            else{
                errorAlert("error-alert-main","Error "+ res.status)
                console.log("Error "+ res.status )
            }

        }
        function clearInput(){
            name.value = ""
            longitude.value = ""
            latitude.value = ""
        }
        function renderPointLi(obj,id){

            let newLi = document.createElement('li');
            newLi.setAttribute('class','list-group-item');
            newLi.innerHTML=`<ul class="list-group">
                <li class="list-group-item" style="cursor: pointer;" id="name-field" data-latitude="${obj.latitude}" data-longitude="${obj.longitude}" onclick="setCenter(this)">${obj.name}</li>
                <li class="list-group-item">${obj.latitude}</li>
                <li class="list-group-item">${obj.longitude}</li>
            </ul>

                <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        data-latitude="${obj.latitude}" data-longitude="${obj.longitude}"
                        data-name="${obj.name}" data-id="${id}" onclick="editPoint(this)"
                >Edit</button>
                <button class="btn btn-outline-danger" onclick="deletePoint(this)" data-id="${id}">Delete</button>`;
            let li_form = document.getElementById("li-form");
            li_form.insertAdjacentElement('afterend', newLi);
        }

        function renderMapPoint(obj,id){
               let newPoint = new ymaps.Placemark([obj.longitude, obj.latitude],
                   {
                    balloonContent: `${obj.name}<br>Широта: ${obj.latitude}<br>Долгота: ${obj.longitude}`,

                },
                   {
                    preset: 'islands#icon',
                    iconColor: '#0095b6'
                })
            newPoint.properties.set('id', id);
            console.log(newPoint);
            myMap.geoObjects.add(newPoint);
            pointCollection.add(newPoint)
        }
        async function deletePoint(point){
            let id = point.getAttribute('data-id');
            // point.closest('li').remove();
            let obj = { id:id };
            const res = await fetch('/delete_point/'+id, {
                method:'DELETE',
                mode: 'cors',
                headers:{
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Content-Type': 'application/json'
                },
                body:JSON.stringify(obj)
            });
            if(res.ok){
                point.closest('li').remove();
                const data = await res.json()
                console.log(data)
                deleteMapPoint(data.id)
            }
            else{
                errorAlert("error-alert-main","Error "+ res.status)
                console.log("Error "+ res.status )
            }


        }
        function deleteMapPoint(id){

             ymaps.geoQuery(pointCollection).search('properties.id='+`"${id}"`).removeFrom(pointCollection);
            ymaps.geoQuery(myMap.geoObjects).search('properties.id='+`"${id}"`).removeFrom(myMap.geoObjects);

        }
        function setCenter(point){
            let longitude = point.getAttribute('data-longitude');
            let latitude = point.getAttribute('data-latitude');
            myMap.setCenter([longitude, latitude]);
            let name_fields = document.querySelectorAll('#name-field')
            name_fields.forEach(item=>{
               item.setAttribute('class','list-group-item')
            });
            point.setAttribute('class','list-group-item active');
        }
        function editPoint(point){
            let longitude = point.getAttribute('data-longitude');
            let latitude = point.getAttribute('data-latitude');
            let name = point.getAttribute('data-name');
            let id = point.getAttribute('data-id');
            let modal = document.querySelector('.modal-body');
            modal.querySelector('#name').value=name;
            modal.querySelector('#latitude').value=latitude;
            modal.querySelector('#longitude').value=longitude;
            modal.querySelector('#id').value=id;
            // console.log(id)

        }
        let btn_modal = document.querySelector("#save-changes");
        btn_modal.addEventListener('click',savePoint);
        async function savePoint(e){
            e.preventDefault();
            let modal = document.querySelector('.modal-body');
            let name = modal.querySelector('#name')
            let latitude =modal.querySelector('#latitude')
            let longitude = modal.querySelector('#longitude')
            let id =modal.querySelector('#id')

            let obj = { name:name.value, latitude:latitude.value, longitude:longitude.value,id:id.value };
            console.log(obj)
            const res = await fetch('/edit_point/'+id.value, {
                method:'PATCH',
                mode: 'cors',
                headers:{
                    'X-CSRF-TOKEN': '{{csrf_token()}}',
                    'Content-Type': 'application/json'
                },
                body:JSON.stringify(obj)
            });
            const data = await res.json()
            if(res.status===422){
                errorAlert("error-alert-edit","Input data is invalid")
                console.log("Error "+ res.status )
            }
            else if(res.ok) {
                console.log(data)
                deleteMapPoint(id.value)
                let point = document.querySelector(`button[data-id='${id.value}']`);
                point.getAttribute('data-id');
                point.closest('li').remove();
                renderMapPoint(obj, data.id)
                renderPointLi(obj, data.id)
            }
            else{
                errorAlert("error-alert-edit","Error "+ res.status)
                console.log("Error "+res.status)
            }
        }

    </script>
@endsection
