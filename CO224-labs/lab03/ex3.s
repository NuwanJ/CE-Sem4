
@ ARM Assembly - exercise 3
@ Written		: Nuwan
@ LastUpdate	: 2018-11-05

	.text 	@ instruction memory

	
@ Write YOUR CODE HERE	

@ ------------------------------------
@ Fibonacci:

@	int fib(int n) 
@	{ 
@  		 if (n <= 1) 
@  	    	return n; 
@  	 	return fib(n-1) + fib(n-2); 
@	} 
@ -----------------------------------

fib :	@ r0 = n

		SUB sp,sp,#12
		STR lr,[sp,#8] 		@ linkRegister 	
		STR r2,[sp,#4] 		@ fib(n-1) 	
		STR r1,[sp,#0] 		@ fib(n-2) 
		
		CMP r0, #1
		BGT el

if:		@ (n<=1)	
		MOV r0, r0

		b endif

el:		@ (n>0)	

		SUB r2, r0, #2	@ n-2
		SUB r1, r0, #1	@ n-1

		MOV r0, r2
		bl fib  		@ r2 = f(n-2)
		MOV r2, r0

		MOV r0, r1
		bl fib			@ r1 = f(n-1)
		MOV r1, r0

		ADD r0, r1, r2;	@ r0 = r1 + r2
		
endif:	LDR r1, [sp,#0]
		LDR r2, [sp,#4]
		LDR lr, [sp,#8]
		ADD sp,sp,#12

		MOV pc,lr

@ ---------------------
	
	.global main
main:
	@ stack handling, will discuss later
	@ push (store) lr to the stack
	sub sp, sp, #4
	str lr, [sp, #0]

	mov r4, #7 	@the value n

	@ calling the Fibonacci function
	mov r0, r4 	@the ag1 load
	bl fib
	mov r5,r0
	

	@ load aguments and print
	ldr r0, =format
	mov r1, r4
	mov r2, r5
	bl printf

	@ stack handling (pop lr from the stack) and return
	ldr lr, [sp, #0]
	add sp, sp, #4
	mov pc, lr

	.data	@ data memory
format: .asciz "F_%d is %d\n"

