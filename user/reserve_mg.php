<?php
include_once("./nabar.php");
?>

<section class="home">
    <div class="text">จัดการจองคิว</div>

    <!-- ส่วนต่าราง -->
    <div class="container" style="background-color:ghostwhite;padding: 10px">
        <span style="color:red">* กรุณายกเลิกบริการก่อนเวลา 1 ชั่วโมง * </span>
        <table class="table table-striped " id="example">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>บริการ</th>
                    <th>ช่าง</th>
                    <th>วันที่</th>
                    <th>สถานะ</th>
                    <th>ตัวเลือก</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $i = null;
                $sql_search = "SELECT *,DATE_FORMAT(re.dateTime_reserve, '%H:%i %W %e %M  %Y') as data_ , re.status as sta FROM `reserve` as re INNER JOIN hairstyle as hstly ON hstly.id_style = re.id_style INNER JOIN hairdresser as hser ON hser.id_hai = re.id_hai WHERE re.id_user = '$ID' AND re.status != 0 AND re.status != 3;";
                foreach (Database::query($sql_search, PDO::FETCH_OBJ) as $row) :
                    ++$i;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->name_style ?></td>
                        <td><?php echo $row->name_hai ?></td>
                        <td><?php echo $row->data_ ?></td>
                        <td><?php
                            $st = $row->sta;
                            if ($st == 1) :
                                echo '<span class="btn-sm btn-success">ยืนยันแล้ว</span>';
                            // echo $row->sta;
                            else :
                                echo '<span class="btn-sm btn-warning">รอยืนยัน</span>';
                            endif;
                            ?></td>
                        <td> <button class="btn  btn-sm btn-danger " onclick="delete_reserve(<?php echo $row->id_reserve ?>)">ยกเลิก</button></td>
                        <!-- <button class="btn btn-primary btn-sm ">แก้ไข</button> -->
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>

    </div>

    <div class="text">บริการเสร็จสิ้น</div>

    <!-- ส่วนต่าราง -->
    <div class="container" style="background-color:ghostwhite;padding: 10px">
        <!-- <span style="color:red">* กรุณายกเลิกบริการก่อนเวลา 1 ชั่วโมง * </span> -->
        <table class="table table-striped " id="example1">
            <thead>
                <tr>
                    <th>ลำดับ</th>
                    <th>บริการ</th>
                    <th>ช่าง</th>
                    <th>วันที่จอง</th>
                    <!-- <th>สถานะ</th> -->
                </tr>
            </thead>
            <tbody>

                <?php
                $i = null;
                $sql_search = "SELECT *,DATE_FORMAT(re.dateTime_reserve, '%H:%i %W %e %M  %Y') as data_ , re.status as sta FROM `reserve` as re INNER JOIN hairstyle as hstly ON hstly.id_style = re.id_style INNER JOIN hairdresser as hser ON hser.id_hai = re.id_hai WHERE re.id_user = '$ID' AND re.status = 0;";
                foreach (Database::query($sql_search, PDO::FETCH_OBJ) as $row) :
                    ++$i;
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $row->name_style ?></td>
                        <td><?php echo $row->name_hai ?></td>
                        <td><?php echo $row->data_ ?></td>
                    </tr>
                <?php
                endforeach;
                ?>
            </tbody>
        </table>

    </div>
</section>
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
        $("#example1").DataTable({
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

    function delete_reserve(id) {
        if (confirm("Are you sure you want to delete this!")) {
            $.ajax({
                url: "./controller/reserve_cl.php",
                type: "POST",
                data: {
                    key: 'delete_reserve',
                    id: id
                },
                success: function(result) {
                    // alert(result);
                    if (result == "success") {
                        alert('ลบสำเร็จ')
                        location.reload();
                    } else {
                        alert('พบข้อผิดพลาด')
                    }

                },
                error: function(result) {
                    alert('พบข้อผิดพลาด')

                }
            })
        }
    }
</script>
<?php
include_once("./footer.php");
?>