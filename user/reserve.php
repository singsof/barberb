<?php
include_once("./nabar.php");
date_default_timezone_set("Asia/Bangkok");
?>
<link href='../plugins/fullcalendar/main.css' rel='stylesheet' />
<script src='../plugins/fullcalendar/main.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'Asia/Bangkok',
            firstDay: 0,
            dateClick: function(info) {
                // alert('Date: ' + info.dateStr);
                // alert('Resource ID: ' + info.resource.id);
                $("#date-input").val(info.dateStr);

            },
            headerToolbar: {
                left: 'title',
                center: 'dayGridMonth,listWeek',
                right: 'today prev,next'

            },
            locale: 'th',
            initialDate: new Date(),
            editable: false,
            eventLimit: true,
            navLinks: false, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            select: function(arg) {
                $('#reserve_table').modal('show');
                $("#dataStarts").val(convert(arg.start));
                calendar.unselect()
            },
            dayMaxEvents: true, // allow "more" link when too many events
            events: {
                url: "php/get-events.php",
                failure: function() {
                    document.getElementById('script-warning').style.display = 'block'
                }
            },
            loading: function(bool) {
                document.getElementById('loading').style.display =
                    bool ? 'block' : 'none';
            },
            eventTimeFormat: { // รูปแบบการแสดงของเวลา เช่น '14:30' 
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        });

        calendar.render();
        calendar.setOption('themeSystem', 'lux');
    });
</script>
<style>
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 14px;
    }

    #script-warning {
        display: none;
        background: #eee;
        border-bottom: 1px solid #ddd;
        padding: 0 10px;
        line-height: 40px;
        text-align: center;
        font-weight: bold;
        font-size: 12px;
        color: red;
    }

    #loading {
        display: none;
        position: absolute;
        top: 10px;
        right: 10px;
    }

    #calendar {
        max-width: 700px;
        margin: 40px auto;
        padding: 0 10px;
    }
</style>
<section class="home">
    <div class="container">
        <div class="text">ปฏิทินจองคิว</div>

        <div class="row">
            <div class="col-md-8">
                <div id='script-warning'>
                    <code>php/get-events.php</code> must be running.
                </div>

                <div id='loading'>loading...</div>
                <div id='calendar' style="background-color:ghostwhite; padding: 10px "></div>
            </div>
            <div class="col-md-4" style="background-color:ghostwhite; padding: 10px ">
                <div class="text">จองคิว</div>
                <form id="form-reserve" action="javascript:void(0)" method="post">
                    <input type="hidden" name="id_user" value="<?php echo $ID; ?>" required>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">เลือกวันจอง:</label>
                        <input id="date-input" name="date_reserve" type="date" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">เลือกเวลาจอง:</label>
                        <input id="Time_reserve" type="time" name="Time_reserve" class="form-control" value="09:00" required>
                    </div>

                    <div class=" mb-3">
                        <label for="recipient-name" class="col-form-label">เลือกช่าง:</label>
                        <select id="id_hai" class="form-select" name="id_hai" required>
                            <option value="">กรุณาเลือกช่าง</option>
                            <?php
                            foreach (Database::query("SELECT * FROM `hairdresser` WHERE status_hai != 0 ", PDO::FETCH_OBJ) as $row) :
                            ?>
                                <option value="<?php echo $row->id_hai ?>"><?php echo $row->name_hai ?></option>

                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>

                    <script>
                        $("#id_hai").change(function() {

                            var id_hai = $(this).val();

                            $('#id_style')
                                .find('option')
                                .remove()
                                .end()
                                .append('<option value="">เลือกบริการ</option>')
                                .val('');
                            $.ajax({
                                url: "./controller/reserve_cl.php",
                                type: "POST",
                                data: {
                                    key: "geser",
                                    id_hai: $(this).val()
                                },
                                success: function(result, textStatus, jqXHR) {
                                    const obj = JSON.parse(result);

                                    if (obj.length > 0) {
                                        $.each(obj, function(index, value) {
                                            console.log(value);
                                            $('#id_style').append($('<option>').val(value.id_style).text(value.name_style));
                                        });
                                    } else {
                                        $('#id_style')
                                            .find('option')
                                            .remove()
                                            .end()
                                            .append('<option value="">เลือกบริการ</option>')
                                            .val('');
                                    }
                                },
                                error: function(result, textStatus, jqXHR) {
                                    alert("ระบบตรวจพบข้อผิดพลาดจาก **register_cm**")
                                }

                            });
                        });
                    </script>

                    <div class=" mb-3">
                        <label for="recipient-name" class="col-form-label">เลือกบริการ </label><span style="color:red;"> * แต่ละบริการใช้เวลาประมาณ 45 นาที * </span>
                        <select id="id_style" class="form-select" name="id_style" required>
                            <option value="">เลือกบริการ</option>
                        </select>
                    </div>


                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">จองคิว</button>
                    </div>

                </form>


                <script>
                    $(document).ready(function() {
                        $("#dataStarts").val(convert(new Date()));



                    })


                    function Time_reserve() {
                        var value = $("#Time_reserve").val();

                        var dateTimeStart = new Date($("#date-input").val() + ' ' + $("#Time_reserve").val());
                        var gHS = dateTimeStart.getHours();
                        var gMS = dateTimeStart.getMinutes();


                        var timeEnd_add = new Date(dateTimeStart);
                        timeEnd_add.setMinutes(dateTimeStart.getMinutes() + 40);

                        var tEgM = "" + timeEnd_add.getMinutes();
                        var sNews = tEgM.length == 1 ? "0" + tEgM : tEgM;

                        if (gHS >= 9 && gHS <= 21) {
                            return true;
                        } else {
                            return false;
                        }
                    }

                    $("#Time_reserve").change(function() {
                        var value = $("#Time_reserve").val();

                        var dateTimeStart = new Date($("#date-input").val() + ' ' + $("#Time_reserve").val());
                        var gHS = dateTimeStart.getHours();
                        var gMS = dateTimeStart.getMinutes();


                        var timeEnd_add = new Date(dateTimeStart);
                        timeEnd_add.setMinutes(dateTimeStart.getMinutes() + 40);

                        var tEgM = "" + timeEnd_add.getMinutes();
                        var sNews = tEgM.length == 1 ? "0" + tEgM : tEgM;

                        if (gHS >= 9 && gHS <= 21) {
                            return;
                        } else {
                            alert("กรุณาเลือกเลือกเวลา 09:00 - 21:00")
                        }
                    });




                    $("#form-reserve").submit(function() {
                        var $inputs = $("#form-reserve :input");
                        var values = {};
                        $inputs.each(function() {
                            values[this.name] = $(this).val();
                        })

                        var datetime = new Date(values['date_reserve'] + ' ' + values['Time_reserve']);
                        datetime.setMinutes(datetime.getMinutes() + 30);

                        console.log(values);

                        if (Time_reserve() == false) {
                            alert("กรุณาเลือกเลือกเวลา 09:00 - 21:00")
                            return;
                        }

                        var sd = "" + datetime.getMinutes();
                        var sNews = sd.length == 1 ? "0" + sd : sd;

                        values['Time_reserve_end'] = datetime.getHours() + ":" + sNews;

                        // alert(values['Time_reserve_end'])
                        $.ajax({
                            url: "./controller/reserve_cl.php",
                            type: "POST",

                            data: {
                                key: "form-reserve",
                                data: values,
                            },
                            success: function(result, textStatus, jqXHR) {
                                console.log(result);

                                if (result == "success") {
                                    alert("จองคิวบริการเรียบร้อย")
                                    location.reload();
                                } else {
                                    alert("จองคิวบริการไม่สำเร็จ")
                                }

                            },
                            error: function(jqXHR, textStatus, errorThrown) {

                            }
                        });
                    });
                </script>
            </div>
        </div>

    </div>
</section>



<?php
include_once("./footer.php");
?>