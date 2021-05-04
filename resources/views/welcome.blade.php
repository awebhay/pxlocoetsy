<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="row">

                </div>
                <div class="col-sm"></div>
                <div class="col-sm">
                    <div class="title m-b-md">
                        Tool crawler cực mạnh mẽ
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div>
                        <form method="POST" action="/getLinks">
                            @csrf
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nhập số trang muốn lấy</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Từ Trang, mặc định là 1" name="from_page">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Đến Trang, mặc định là 1" name="to_page">
                                    </div>
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Số Hình Trên 1 Trang, mặc định là 1" name="img_num">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Nhập link cho trang: etsy.com</label>
                                <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="url">
                                <small id="emailHelp" class="form-text text-muted">ví dụ: <a disabled href="https://www.etsy.com/search?q=dog%20shirt">https://www.etsy.com/search?q=dog%20shirt</a>.</small>
                            </div>
                            <button type="submit" class="btn btn-primary">Chạy tiến trình</button>
                            <br>
                        </form>
                        <p></p>
                        <form method="POST" action="/truncate">
                            @csrf
                            <button type="submit" class="btn btn-primary">Xóa hết dữ liệu cũ</button>
                        </form>
                        @error('title')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm"></div>
            </div>
        </div>
    </body>
</html>
