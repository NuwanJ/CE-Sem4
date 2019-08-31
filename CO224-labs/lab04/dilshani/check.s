    	.text
    	.global main

main:
	@push lr to the stack
    	sub sp, sp, #4
    	str lr, [sp, #0]


	@taking inputs

		@input x
    	ldr r0, =inputformat
    	sub sp, sp, #4
    	mov r1, sp
    	bl  scanf

    	@load the value of x to the r4
    	ldr r4, [sp, #0]
    	add sp, sp, #4

    	
    	@input y
    	ldr r0, =inputformat
	   	sub sp, sp, #4
  		mov r1, sp
   		bl  scanf
    	
    	@load the value of y to the r5
    	ldr r5, [sp, #0]
	   	add sp, sp, #4

		@Calculations
		mov r3,r4 
		lsl r3,r5	@x*2^y
		lsr r4,r5   @x/2*y
	
		@Printing results
		ldr r0, =format
    	mov r1,r3
		mov r2,r4
    	bl  printf

		@push lr to the stack
    	ldr lr, [sp, #0]
    	add sp, sp, #4
    	mov pc, lr

    	.data

format:
	.asciz "%d %d\n"
inputformat:
    .asciz "%d"
