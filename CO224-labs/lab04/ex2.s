@ ARM Assembly - exercise 2

@ Written		: Nuwan
@ LastUpdate	: 2018-11-12

	.text 	@ instruction memory
	
@ ---------------------	

	.global main
main:
	@ stack handling, will discuss later
	@ push (store) lr to the stack

	@ Write an ARM Assembly program to read two numbers and print whether 
	@ they are equal or not

    @push lr to the stack
    sub sp, sp, #4
    str lr, [sp, #0]


    @ ------------------------------------------------------------
	@ load 2 numbers as scanf("%d %d", r4, r5);

	SUB sp, sp, #8
	LDR r0, =formats
	@LDR r0, [r0]; 	@ new 

	MOV r1, sp

	BL scanf

	LDR r4, [sp, #0]
	LDR r5, [sp, #4]
	ADD sp, sp, #8

	@ Now compare r1 and r2

	CMP r4, r5
	LDREQ r3, =formateq
	LDRNE r3, =formatneq

	@ Print number 1 and number 2

	LDR r0, =formatp		@ r0=formatp, r1=num1, r2=num2, r3=result
	@LDR r0, [r0];

	MOV r1, r4
	MOV r2, r5

	BL printf

        @----------push lr to the stack---------@
        ldr lr, [sp, #0]
        add sp, sp, #4
        mov pc, lr

	
	.data	@ data memory
formats: .asciz "%d %d"
formatp: .asciz "Two numbers %d and %d are %s"
formateq: .asciz "equal\n"
formatneq: .asciz "not equal\n"
