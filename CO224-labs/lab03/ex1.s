@ ARM Assembly - exercise 1

@ Written		: Nuwan
@ LastUpdate	: 2018-11-05

	.text 	@ instruction memory

	
@ Write YOUR CODE HERE	

@ ---------------------	
@ mypow:
@ implement using loops to calculate (arg1 ^ arg2)


@ --- Begin of mypow ------------------------------------------

		@ save current values on stack

		@ r0 = arg1
		@ r1 = arg2

mypow:	SUB sp, sp, #8
		STR r5,[sp,#4] 		@ sum 	
		STR r4,[sp,#0] 		@ i 
	
		MOV r4, #0			@ i = 0
		MOV r5, r0			@ sum = 0
		SUB r1, r1, #1		@ arg2 = arg2 - 1

FOR: 	cmp r4, r1
		beq EXIT_F			@ if (i == arg2) return;
		
		MUL r5, r0, r5		@ sum += arg1

		ADD r4, r4, #1		@ i = i+1 
		b FOR
EXIT_F:	

		MOV r0, r5			@ r0 = result

		@ replace old values to registers
		LDR r4, [sp,#0]
		LDR r5, [sp,#4]
		ADD sp,sp,#8

		@ return to main routing
		MOV pc,lr

@ --- End of mypow --------------------------------------------





@ ---------------------	

	.global main
main:
	@ stack handling, will discuss later
	@ push (store) lr to the stack
	sub sp, sp, #4
	str lr, [sp, #0]

	mov r4, #3 	@the value x
	mov r5, #3 	@the value n

	@ calling the mypow function
	mov r0, r4 	@the arg1 load
	mov r1, r5 	@the arg2 load
	bl mypow
	mov r6,r0
	

	@ load aguments and print
	ldr r0, =format
	mov r1, r4
	mov r2, r5
	mov r3, r6
	bl printf

	@ stack handling (pop lr from the stack) and return
	ldr lr, [sp, #0]
	add sp, sp, #4
	mov pc, lr

	.data	@ data memory
format: .asciz "%d^%d is %d\n"

