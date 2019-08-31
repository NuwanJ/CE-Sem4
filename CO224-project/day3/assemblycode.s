@ ARM Assembly: Lab 1 - exercise 1 template
@ 
    .text   @ instruction memory
    .global main
main:
    @ stack handling, will discuss later
    @ push (store) lr to the stack
    sub sp, sp, #4
    str lr, [sp, #0]

    @------add------------@

    mov r2, #10
    mov r1, #5

    add r4,r2,r1
    mov r9,r4

    @------sub------------@

    mov r2, #10
    mov r1, #5

    sub r4,r2,r1
    mov r8,r4

   @------and------------@

    mov r2, #10
    mov r1, #5

    and r4,r2,r1
    mov r7,r4

    @------or------------@

    mov r2, #10
    mov r1, #5

    //or r4,r2,r1
    mov r6,r4

    @------mov------------@
    mov r2, #10
    mov r1, #5

    mov r4,r1
    mov r5,r4

    @------loadi------------@

    //ldr r4,0XFF
    mov r4,r4

    @ ---------------------

    b print    
    
    @ load aguments and print

print:  ldr r0, =formatAdd
        mov r1, r9
        bl printf

        ldr r0, =formatSub
        mov r1, r8
        bl printf

        ldr r0, =formatAnd
        mov r1, r7
        bl printf

        ldr r0, =formatOr
        mov r1, r6
        bl printf

        ldr r0, =formatMov
        mov r1, r5
        bl printf

        ldr r0, =formatLoadI
        mov r1, r4
        bl printf

        b exit
                                                                  
    @ stack handling (pop lr from the stack) and return
exit:   ldr lr, [sp, #0]
        add sp, sp, #4
        mov pc, lr

 .data   @ data memory
formatAdd: .asciz "(10+5 = 15) \t>> %d\n"
 .data   @ data memory
formatSub: .asciz "(10-5=5) \t>> %d\n"
 .data   @ data memory
formatAnd: .asciz "(10 AND 5 = 0) \t>> %d\n"
 .data   @ data memory
formatOr: .asciz "(10 OR 5 = 15) \t>> %d\n"
 .data   @ data memory
formatMov: .asciz "(move r1 = 5) \t>> %d\n"
 .data   @ data memory
formatLoadI: .asciz "(load 0xFF) \t>> %d\n"
