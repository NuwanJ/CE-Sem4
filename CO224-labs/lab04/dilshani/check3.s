
    	.text
    	.global main

main:
	@push lr to the stack
    	sub sp, sp, #8
    	str lr, [sp, #0]


	@taking inputs

		@input x
    	ldr r0, =inputformat
    	sub sp, sp, #4
    	mov r1, sp
    	bl  scanf

    	@load the value of x >>>> r4
    	ldr r4, [sp, #0]
    	add sp, sp, #4

    	

		@Calculations
        mov r5,#1
		loop:   cmp r4,r5    
                beq exit
                ldr r0, =format
                mov r1,r5
                bl  printf
                add r5,r5,#1
                b loop

exit:

        ldr r0, =format
        mov r1,r5
        bl  printf

		@push lr to the stack
    	ldr lr, [sp, #0]
    	add sp, sp, #4
    	mov pc, lr

    	.data

format:
	.asciz "%d\n"
inputformat:
    .asciz "%d"
