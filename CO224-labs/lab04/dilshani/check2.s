
        .text
        .global main

main:
    @push lr to the stack
        sub sp, sp, #4
        str lr, [sp, #0]


    @taking inputs

        @--------input x-------@
        ldr r0, =inputformat
        sub sp, sp, #4
        mov r1, sp
        bl  scanf

        @load the value of x >>>>> r4
        ldr r4, [sp, #0]
        add sp, sp, #4

        
        @--------input y---------@
        ldr r0, =inputformat
        sub sp, sp, #4
        mov r1, sp
        bl  scanf
        
        @load the value of y >>>>> r5
        ldr r5, [sp, #0]
        add sp, sp, #4



        @-----------Calculations----------------@
        mov r3,r4 
        cmp r4,r5
        bne elsenot

        if:     ldr r0, =formatif
                b exit


        elsenot:   ldr r0, =formatelse
                b exit 

exit:   bl printf
        

        @----------push lr to the stack---------@
        ldr lr, [sp, #0]
        add sp, sp, #4
        mov pc, lr

        .data

formatif:
    .asciz "equal\n"

formatelse:
    .asciz "not equal\n"

inputformat:
    .asciz "%d"
