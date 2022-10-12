<?php
include_once("./nabar.php");
?>
<section class="home">
    <div class="text">จัดการบริการ</div>
    <div class="container" style="background-color:ghostwhite;padding: 10px">
        <!-- <span style="color:red">* กรุณายกเลิกบริการก่อนเวลา 1 ชั่วโมง * </span> -->
        <table class="table table-striped " id="example">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อบริการ</th>
                    <th>ราคา</th>
                    <th>เวลาให้บริการ</th>
                    <th><a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_services" data-bs-whatever="@mdo">เพิ่มบริการ</a></th>
                </tr>
            </thead>
            <tbody>

                <?php
                $i = null;
                $sql_search = "SELECT * FROM `hairstyle` WHERE status != 0";
                foreach (Database::query($sql_search, PDO::FETCH_OBJ) as $row) :
                    ++$i;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->name_style ?></td>
                        <td><?php echo $row->price_style ?></td>
                        <td><?php echo $row->time_ok ?></td>
                        <td>
                            <?php
                            $btn = "";
                            if ($row->name_style == 'ไม่ขอเลือกบริการ') {
                                $btn = "<button type='button' onclick='link($row->id_style)' class='btn btn-warning btn-sm'>แก้ไข</button> <button type='button' onclick='delete_services($row->id_style)' class='btn btn-danger btn-sm'>ลบ</button>";
                            } else {

                                $btn = "<button type='button' onclick='link($row->id_style)' class='btn btn-warning btn-sm'>แก้ไข</button> <button type='button' onclick='delete_services($row->id_style)' class='btn btn-danger btn-sm'>ลบ</button>";
                            }
                            echo $btn;
                            ?>
                        </td>

                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>

    </div>
</section>

<div class="modal fade" id="add_services" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">เพิ่มบริการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-addServices" action="javascript:void(0)" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">ชื่อบริการ</label>
                        <input type="text" class="form-control" name="name_style" required>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">ราคา</label>
                        <input type="number" min="0" class="form-control" name="price_style" required>

                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">เวลาให้บริการ</label>
                        <input type="number" min="0" class="form-control" name="time_ok" required>
                    </div>
                    <div class="mb-3">
                        <label for="message-text" class="col-form-label">เลือกช่าง</label>
                        <select name="id_hai" class="form-control" required>
                            <option value="">เลือกช่าง</option>
                            <?php
                            $sql_search = "SELECT * FROM `hairdresser` WHERE status_hai != 0 ";
                            foreach (Database::query($sql_search, PDO::FETCH_OBJ) as $row) :
                            ?>
                                <option value="<?php echo $row->id_hai?>"><?php echo $row->name_hai ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-primary">เพิ่มบริการ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $("#form-addServices").submit(function() {
        // alert("sldfj")
        // var inputs = $("#form-addServices : input");
        var $inputs = $("#form-addServices :input");
        var values = {};
        $inputs.each(function() {
            values[this.name] = $(this).val();
        })
        console.log(values);
        $.ajax({
            url: "./controller/services.php",
            type: "POST",
            data: {
                key: "form-addServices",
                data: values,
            },
            success: function(result, textStatus, jqXHR) {
                console.log(result);
                if (result == "success") {
                    alert("เพิ่มบริการสำเร็จ");
                    location.reload();
                } else {
                    alert("เพิ่มบริการไม่สำเร็จ");
                    location.reload();

                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert("เพิ่มบริการไม่สำเร็จ");
                location.reload();
            }
        });

    });

    function delete_services(id) {
        if (confirm('Are you sure you want to delete')) {
            $.ajax({
                url: "./controller/services.php",
                type: "POST",
                data: {
                    key: "delete_services",
                    id: id
                },
                success: function(result, textStatus, status) {
                    // alert(result);
                    if (result == "success") {
                        alert("ลบบริการสำเร็จ")
                        location.reload();
                    } else {
                        alert("ลบบริการไม่สำเร็จ")
                        location.reload();
                    }
                },
                error: function(result, textStatus) {

                }
            });
        }
    }

    function link(id) {
        location.assign('edit_ser.php?id=' + id);
    }
</script>
<script>
    $(document).ready(function() {
        $("#example").DataTable({
            dom: 'lBfrtip',
            lengthMenu: [
                [10, 25, 50, 60, -1],
                [10, 25, 50, 60, "All"]
            ],
            language: {
                sProcessing: " กำลังดำเนินการ...",
                sLengthMenu: " แสดง  _MENU_  แถว ",
                sZeroRecords: " ไม่พบข้อมูล ",
                sInfo: " แสดง _START_ ถึง _END_ จาก _TOTAL_ แถว ",
                sInfoEmpty: "แสดง 0 ถึง 0 จาก 0 แถว",
                sInfoFiltered: "( กรองข้อมูล  _MAX_  ทุกแถว )",
                sInfoPostFix: "",
                sSearch: "ค้นหา:",
                sUrl: "",
                oPaginate: {
                    "sFirst": " เริ่มต้น ",
                    "sPrevious": " ก่อนหน้า ",
                    "sNext": " ถัดไป ",
                    "sLast": " สุดท้าย "
                }
            }, // sInfoEmpty: "แสดง 0 ถึง 0 ของ 0 เร็คคอร์ด",
            processing: true, // แสดงข้อความกำลังดำเนินการ กรณีข้อมูลมีมากๆ จะสังเกตเห็นง่าย
            //serverSide: true, // ใช้งานในโหมด Server-side processing
            // กำหนดให้ไม่ต้องการส่งการเรียงข้อมูลค่าเริ่มต้น จะใช้ค่าเริ่มต้นตามค่าที่กำหนดในไฟล์ php

            buttons: [],
            retrieve: true,
        });
    });
</script>
<?php
include_once("./footer.php");
?>