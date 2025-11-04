<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
    .card {
        margin-bottom: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
        height: 50px;
    }

    .card-body {
        /* padding: 20px; */
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    .card-title {
        padding-top: 10px;
        font-size: 18px;
        font-weight: bold;
        text-align: center;
    }

    .card-text {
        margin-top: 10px;
    }
    </style>
</head>

<body>
    <div class="container">

        <div class="row" style="margin-top:30px;">

            <div class="col-md-2">
                <label for="formrow-email-input" class="form-label">Location Name</label>
                <select class="form-control" name="transId" id="transId">
                    <option>--Select--</option>
                    @foreach($MachineLocData as $mcloc)
                    <option value="{{$mcloc->transId}}">
                        {{$mcloc->toLocName}}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="formrow-email-input" class="form-label">Machine Make</label>
                <select class=" form-control " name=" mc_make_Id" id="mc_make_Id">
                    <option>--Select--</option>
                    @foreach($MachineMakeData as $mcmake)
                    <option value="{{$mcmake->machine_make_name}}">
                        {{$mcmake->machine_make_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="formrow-email-input" class="form-label">Machine main Type</label>
                <select class=" form-control " name=" machine_Id" id="machine_Id">
                    <option>--Select--</option>
                    @foreach($MachineMainTypeData as $mcmaintype)
                    <option value="{{$mcmaintype->machine_Id}}">
                        {{$mcmaintype->machine_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="formrow-email-input" class="form-label">Machine Type</label>
                <select class="form-control" name="machinetype_id" id="machinetype_id">
                    <option>--Select--</option>
                    @foreach($MachineTypeData as $mctype)
                    <option value="{{$mctype->machinetype_name}}">
                        {{$mctype->machinetype_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="formrow-email-input" class="form-label">Inward Type</label>
                <select name="inwardtypeId" id="inwardtypeId" class="form-control">
                    <option>--Select--</option>
                    @foreach($RentedData as $rent)
                    <option value="{{$rent->inwardtypeId}}">
                        {{$rent->inwardtypeName}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2" style="margin-top:30px;">
                <button type="submit" class="btn btn-primary w-md">Search</button>
                <a href="/maintance_dashboard" class="btn btn-danger">Clear</a>
            </div>

        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-1">
                <div class="card myCard" data-toggle="modal" data-target="#myModal">
                    <div class="card-body">
                        <h5 class="card-title">1</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard" data-toggle="modal" data-target="#myModal">
                    <div class="card-body">
                        <h5 class="card-title">2</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard" data-toggle="modal" data-target="#myModal">
                    <div class="card-body">
                        <h5 class="card-title">3</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard" data-toggle="modal" data-target="#myModal">
                    <div class="card-body">
                        <h5 class="card-title">4</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">5</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">6</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">7</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">8</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">9</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">10</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">11</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card myCard">
                    <div class="card-body">
                        <h5 class="card-title">12</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">13</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">14</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">15</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">16</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">17</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">18</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">19</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">20</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">21</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">22</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">23</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">24</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">25</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">26</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">27</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">28</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">29</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">30</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</body>
<div class="modal fade" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal Title</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Machine Basic Information</label><span id="ticketId"
                                name="ticketId"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Location Information</label><span id="taskTitle" name="taskTitle"></span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Preventive Last Date & Next Date</label><span id="dept_id" name="dept_id"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    // Select the card element using its ID
    var $card = $('.myCard');

    // Generate a random color
    var randomColor = '#' + Math.floor(Math.random() * 16777215).toString(16);

    // Change the background color of the card using .css()
    $card.css('background-color', randomColor);
});
</script>

</html>